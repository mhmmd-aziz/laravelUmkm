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
        $query = $validated['prompt'];
        
        $tokoId = Auth::user()->toko->id;

        // 1. Kumpulkan Data Penjualan (HANYA dari status 'selesai')
        $queryOmset = Transaksi::where('toko_id', $tokoId)
            ->where('status_transaksi', 'selesai');

        $omsetBulanIni = (clone $queryOmset)->whereMonth('updated_at', Carbon::now()->month)
            ->sum('total_harga');

        $produkTerlaris = Produk::where('toko_id', $tokoId)
            ->withCount(['detailTransaksis' => function ($q) {
                // Pastikan detail transaksi juga dari pesanan 'selesai'
                $q->whereHas('transaksi', function ($subQ) {
                    $subQ->where('status_transaksi', 'selesai');
                });
            }])
            ->orderBy('detail_transaksis_count', 'desc')
            ->take(5)
            ->get(['nama_produk', 'detail_transaksis_count']);

        // --- Guard Clause (Jika data kosong) ---
        if ($omsetBulanIni == 0 && $produkTerlaris->sum('detail_transaksis_count') == 0) {
            return response()->json([
                'reply' => 'Tentu! Tapi sepertinya Anda belum memiliki data penjualan (pesanan berstatus "selesai") yang bisa saya analisis. Silakan kembali lagi setelah ada penjualan yang tuntas.'
            ]);
        }
            
        $dataPenjualan = [
            'total_omset_bulan_ini_rp' => $omsetBulanIni,
            'top_5_produk_terlaris_bulan_ini' => $produkTerlaris->toArray()
        ];
        
        // Kirim JSON sebagai satu baris
        $dataJson = json_encode($dataPenjualan);

        // 2. System Prompt (Peran untuk AI)
        // --- INI PERBAIKANNYA (Prompt lebih tegas) ---
        $systemPrompt = "Anda adalah Analis Bisnis AI yang cerdas dan suportif untuk pemilik UMKM Budaya Indonesia.
        TUGAS ANDA:
        1.  **WAJIB BALAS DALAM BAHASA INDONESIA.**
        2.  **WAJIB BALAS SEBAGAI TEKS BIASA.** JANGAN gunakan Markdown (seperti `**` atau `*`).
        3.  Anda akan diberi data penjualan (JSON) dan pertanyaan dari penjual.
        4.  Jawab pertanyaan penjual HANYA berdasarkan data yang diberikan.
        5.  Berikan jawaban yang memotivasi dan actionable (bisa ditindaklanjuti).

        DATA PENJUALAN (HANYA DARI PESANAN 'SELESAI'):
        $dataJson";
        
        try {
            // 3. Panggil API Ollama (Lokal)
            $response = Http::timeout(120) // Timeout 2 menit
                ->baseUrl('http://localhost:11434/api')
                ->post('/chat', [ // Gunakan endpoint /chat
                    'model' => 'llama3:8b', 
                    'stream' => false,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $query]
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Error Ollama API (Insight): ' . $response->body());
                return response()->json(['reply' => 'Maaf, Analis AI sedang mengalami gangguan (Server).'], 500);
            }
            
            $reply = $response->json()['message']['content'] ?? 'Maaf, saya tidak bisa memproses balasan saat ini.';
            
            // --- PERBAIKAN: Bersihkan Markdown ---
            $reply = str_replace(['**', '*'], '', $reply);
            $reply = trim(preg_replace('/^Here is.*?\n/', '', $reply)); // Hapus "Here is the analysis..."

            return response()->json([
                'reply' => trim($reply)
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Insight): '. $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, Analis AI sedang mengalami gangguan teknis (Tidak bisa terhubung ke Ollama). Pastikan Ollama sudah berjalan.'
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
        
        // --- INI DIA PERBAIKANNYA (Prompt Bahasa Indonesia lebih tegas) ---
        $prompt = "Anda adalah copywriter AI untuk UMKM Kriya Budaya Indonesia.
        TUGAS ANDA:
        1.  **WAJIB TULIS DALAM BAHASA INDONESIA.**
        2.  **WAJIB TULIS SEBAGAI TEKS BIASA.** JANGAN gunakan Markdown (seperti `**` atau `*`).
        3.  Buatkan draf deskripsi produk untuk e-commerce.

        Data Produk:
        Nama Produk: '{$validated['nama_produk']}'
        Kategori: '{$validated['kategori']}'
        
        Tulis dalam 2 bagian, pisahkan jawaban HANYA dengan '---PEMISAH---'.
        
        Format Jawaban (WAJIB IKUTI, JANGAN TAMBAHKAN KATA PEMBUKA SEPERTI 'Tentu!' atau 'Ini drafnya:'):
(Deskripsi singkat 1-2 kalimat di sini)
---PEMISAH---
(Deskripsi lengkap 2 paragraf di sini)";
        // --- BATAS PERBAIKAN ---

        try {
            // Panggil API Ollama (Lokal)
             $response = Http::timeout(120) // Timeout 2 menit
                ->baseUrl('http://localhost:11434/api')
                ->post('/chat', [ // Gunakan endpoint /chat
                    'model' => 'llama3:8b', 
                    'stream' => false,
                    'messages' => [
                        // Kita gabungkan jadi 1 prompt 'user' agar lebih stabil
                        ['role' => 'user', 'content' => $prompt] 
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Error Ollama API (Copywriting): ' . $response->body());
                return response()->json(['error' => 'Gagal membuat deskripsi (Server).'], 500);
            }

            $text = $response->json()['message']['content'] ?? '';
            
            // --- PERBAIKAN: Parsing (pemilahan) yang lebih aman ---
            $parts = explode('---PEMISAH---', $text, 2); // Limit 2
            
            // Hapus semua Markdown dan kata pembuka
            $deskripsiSingkat = trim(str_replace(['**', '*', 'Deskripsi Singkat (1-2 kalimat persuasif):'], '', $parts[0] ?? 'Deskripsi singkat tidak tersedia.'));
            
            // Cek jika $parts[1] ada
            if (isset($parts[1])) {
                $deskripsiLengkap = trim(str_replace(['**', '*', 'Deskripsi Lengkap (2 paragraf detail):'], '', $parts[1]));
            } else {
                // Jika AI tidak pakai ---PEMISAH---, kita ambil sisa teksnya
                $deskripsiLengkap = trim(str_replace(['**', '*', 'Deskripsi Lengkap (2 paragraf detail):'], '', str_replace($deskripsiSingkat, '', $text)));
                if(empty($deskripsiLengkap)) {
                     $deskripsiLengkap = 'Deskripsi lengkap tidak tersedia.';
                }
            }
            // --- BATAS PERBAIKAN ---

            return response()->json([
                'deskripsi_singkat' => $deskripsiSingkat,
                'deskripsi_lengkap' => $deskripsiLengkap
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Copywriting): ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat deskripsi. Pastikan Ollama sudah berjalan.'], 500);
        }
    }
}