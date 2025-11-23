<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\View\View;

class AiInsightController extends Controller
{
    /**
     * Menampilkan halaman AI Insight.
     */
    public function index(): View
    {
        return view('penjual.ai.index');
    }

    /**
     * Menganalisis data penjualan dan memberikan insight.
     */
    public function getInsight(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);
        $userQuery = $validated['prompt'];
        
        $tokoId = Auth::user()->toko->id;

        // 1. Kumpulkan Data Penjualan
        $queryOmset = Transaksi::where('toko_id', $tokoId)
            ->where('status_transaksi', 'selesai');

        $omsetBulanIni = (clone $queryOmset)->whereMonth('updated_at', Carbon::now()->month)
            ->sum('total_harga');

        $produkTerlaris = Produk::where('toko_id', $tokoId)
            ->withCount(['detailTransaksis' => function ($q) {
                $q->whereHas('transaksi', function ($subQ) {
                    $subQ->where('status_transaksi', 'selesai');
                });
            }])
            ->orderBy('detail_transaksis_count', 'desc')
            ->take(5)
            ->get(['nama_produk', 'detail_transaksis_count']);

        // Guard Clause
        if ($omsetBulanIni == 0 && $produkTerlaris->sum('detail_transaksis_count') == 0) {
            return response()->json([
                'reply' => 'Saat ini belum ada data penjualan berstatus "selesai" yang bisa dianalisis. Silakan kembali setelah ada transaksi berhasil.'
            ]);
        }
            
        $dataPenjualan = [
            'omset_bulan_ini' => 'Rp ' . number_format($omsetBulanIni, 0, ',', '.'),
            'top_produk' => $produkTerlaris->map(function($p) {
                return $p->nama_produk . ' (' . $p->detail_transaksis_count . ' terjual)';
            })->toArray()
        ];
        
        $dataJson = json_encode($dataPenjualan);

        // 2. System Prompt (DIPERBAIKI: LEBIH TEGAS & SPESIFIK)
        // Trik: Gunakan instruksi bahasa Inggris untuk aturan (Llama 3 lebih patuh instruksi EN),
        // tapi perintahkan OUTPUT dalam bahasa Indonesia.
        $systemPrompt = "You are an expert Business Analyst for Indonesian MSMEs (UMKM).
        
        CONTEXT DATA (JSON):
        $dataJson

        INSTRUCTIONS:
        1. Analyze the DATA provided above to answer the user's question.
        2. **CRITICAL:** YOUR RESPONSE MUST BE 100% IN INDONESIAN LANGUAGE. DO NOT USE ENGLISH.
        3. **FORMAT:** Use plain text only. Do NOT use Markdown (no bold **, no italics *).
        4. Keep the tone professional, encouraging, and actionable.
        5. If the user asks about something not in the data, say you only know about sales data.
        
        User Question: \"$userQuery\"
        
        Answer in Indonesian:";
        
        try {
            // 3. Panggil API Ollama
            $response = Http::timeout(120)
                ->baseUrl('http://localhost:11434/api')
                ->post('/chat', [
                    'model' => 'llama3:8b', 
                    'stream' => false,
                    // -- TAMBAHAN PENTING --
                    'temperature' => 0.3, // Rendahkan suhu agar AI tidak "halusinasi" bahasa
                    // ----------------------
                    'messages' => [
                        ['role' => 'user', 'content' => $systemPrompt] // Gabung jadi satu prompt user agar lebih kuat
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Error Ollama API (Insight): ' . $response->body());
                return response()->json(['reply' => 'Maaf, server AI sedang sibuk.'], 500);
            }
            
            $reply = $response->json()['message']['content'] ?? 'Tidak ada balasan.';
            
            // Bersihkan sisa-sisa Markdown
            $reply = str_replace(['**', '*', '#', '`'], '', $reply);
            
            return response()->json([
                'reply' => trim($reply)
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Insight): '. $e->getMessage());
            return response()->json([
                'reply' => 'Terjadi kesalahan koneksi ke AI Lokal.'
            ], 500);
        }
    }

    /**
     * Membuat draf deskripsi produk menggunakan AI.
     */
    public function generateDeskripsi(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
        ]);
        
        // --- PROMPT DIPERBAIKI (TEKNIK ROLE-PLAYING KUAT) ---
        $prompt = "You are a professional Indonesian Copywriter.
        Task: Create a product description for an e-commerce site.
        
        Product Details:
        - Name: '{$validated['nama_produk']}'
        - Category: '{$validated['kategori']}'
        
        STRICT RULES:
        1. **OUTPUT MUST BE IN INDONESIAN LANGUAGE ONLY.**
        2. Do NOT use Markdown formatting (no ** bold).
        3. Output format must be exactly split by '---PEMISAH---'.
        4. Part 1: Short description (persuasive, 1-2 sentences).
        5. Part 2: Long description (detailed, 2 paragraphs).
        
        Output Pattern:
        [Short Description Bahasa Indonesia]
        ---PEMISAH---
        [Long Description Bahasa Indonesia]";

        try {
            $response = Http::timeout(120)
                ->baseUrl('http://localhost:11434/api')
                ->post('/chat', [
                    'model' => 'llama3:8b', 
                    'stream' => false,
                    // -- TAMBAHAN PENTING --
                    'temperature' => 0.4, // Sedikit lebih kreatif dari insight, tapi tetap terkontrol
                    // ----------------------
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt] 
                    ]
                ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'Gagal memproses AI.'], 500);
            }

            $text = $response->json()['message']['content'] ?? '';
            
            // Parsing hasil
            $parts = explode('---PEMISAH---', $text, 2);
            
            $deskripsiSingkat = trim(str_replace(['**', '*', '"'], '', $parts[0] ?? ''));
            $deskripsiLengkap = isset($parts[1]) ? trim(str_replace(['**', '*', '"'], '', $parts[1])) : '';

            // Fallback jika AI gagal memisah
            if (empty($deskripsiLengkap)) {
                $deskripsiLengkap = $deskripsiSingkat; 
                $deskripsiSingkat = Str::limit($deskripsiSingkat, 100);
            }

            return response()->json([
                'deskripsi_singkat' => $deskripsiSingkat,
                'deskripsi_lengkap' => $deskripsiLengkap
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Copywriting): ' . $e->getMessage());
            return response()->json(['error' => 'Koneksi ke AI gagal.'], 500);
        }
    }
}