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
use Illuminate\Support\Str;

class AiInsightController extends Controller
{
    public function index(): View
    {
        return view('penjual.ai.index');
    }

    // --- HELPER PRIVATE UNTUK KONEKSI KE GROQ ---
    private function callGroqApi($messages, $temperature = 0.5)
    {
        $apiKey = env('GROQ_API_KEY');
        $model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');

        // Kita gunakan endpoint Groq yang kompatibel dengan OpenAI
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => $temperature,
            'max_tokens'  => 1024,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'] ?? null;
        }

        Log::error('Groq API Error: ' . $response->body());
        return null;
    }

    // --- FITUR 1: INSIGHT PENJUALAN (DATABASE INTEGRATED) ---
    public function getInsight(Request $request)
    {
        $validated = $request->validate(['prompt' => 'required|string|max:1000']);
        $userQuery = $validated['prompt'];
        $tokoId = Auth::user()->toko->id;

        // 1. AMBIL DATA DARI DATABASE (INTEGRASI NYATA)
        $queryOmset = Transaksi::where('toko_id', $tokoId)->where('status_transaksi', 'selesai');
        $omsetBulanIni = (clone $queryOmset)->whereMonth('updated_at', Carbon::now()->month)->sum('total_harga');

        $produkTerlaris = Produk::where('toko_id', $tokoId)
            ->withCount(['detailTransaksis' => function ($q) {
                $q->whereHas('transaksi', function ($subQ) { $subQ->where('status_transaksi', 'selesai'); });
            }])
            ->orderBy('detail_transaksis_count', 'desc')
            ->take(5)
            ->get(['nama_produk', 'detail_transaksis_count']);

        // Format data agar bisa dibaca AI
        $dataPenjualan = [
            'bulan_ini' => Carbon::now()->format('F Y'),
            'omset_bulan_ini' => 'Rp ' . number_format($omsetBulanIni, 0, ',', '.'),
            'top_produk' => $produkTerlaris->map(function($p) {
                return $p->nama_produk . ' (' . $p->detail_transaksis_count . ' terjual)';
            })->toArray()
        ];
        $dataJson = json_encode($dataPenjualan);

        // 2. SETTING IDENTITAS AI (RUPA NUSANTARA)
        $systemPrompt = "
        IDENTITAS:
        Anda adalah AI Business Analyst khusus untuk platform 'Rupa Nusantara', sebuah e-commerce budaya Indonesia karya Muhammad Aziz.
        Tugas Anda adalah menganalisis performa toko milik user berdasarkan data nyata.

        DATA TOKO USER (JSON):
        $dataJson

        INSTRUKSI:
        1. Jawab pertanyaan user berdasarkan DATA di atas.
        2. Gunakan Bahasa Indonesia yang profesional namun menyemangati.
        3. JANGAN gunakan format Markdown (seperti **bold** atau *italic*), gunakan teks biasa saja.
        4. Jika user bertanya siapa pembuat sistem ini, jawab dengan bangga: 'Platform ini dibuat dan dikembangkan oleh: Tim Out of The Box : yaitu 
        Muhammad Aziz (ketua)
        Amirullah (anngota)
        Deswita Nazwa Ariani (anngota)
        Mirza Feberani (anngota)
        Muhammad Alif Arrayyan (anngota)
        Kami Berasal Dari Kampus Politeknik Negeri Lhokseumawe.'.
        ";

        // 3. KIRIM KE GROQ
        try {
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userQuery]
            ];
            
            // Suhu 0.3 agar analisis datanya akurat (tidak ngarang)
            $reply = $this->callGroqApi($messages, 0.3); 

            if (!$reply) return response()->json(['reply' => 'Maaf, server AI sedang sibuk.'], 500);
            
            // Bersihkan sisa markdown
            $reply = str_replace(['**', '*', '#', '`'], '', $reply);
            
            return response()->json(['reply' => trim($reply)]);

        } catch (\Exception $e) {
            Log::error('Error Groq Insight: '. $e->getMessage());
            return response()->json(['reply' => 'Terjadi kesalahan koneksi.'], 500);
        }
    }

    // --- FITUR 2: COPYWRITING ---
    public function generateDeskripsi(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
        ]);
        
        $prompt = "You are a Copywriter for 'Rupa Nusantara' (Indonesian Culture E-commerce).
        Product: '{$validated['nama_produk']}' (Category: {$validated['kategori']}).
        
        RULES:
        1. OUTPUT IN INDONESIAN ONLY.
        2. NO Markdown formatting.
        3. Format: [Short Description] ---PEMISAH--- [Long Description 2 paragraphs].";

        try {
            $messages = [['role' => 'user', 'content' => $prompt]];
            $text = $this->callGroqApi($messages, 0.6); // Lebih kreatif

            if (!$text) return response()->json(['error' => 'Gagal.'], 500);
            
            $parts = explode('---PEMISAH---', $text, 2);
            return response()->json([
                'deskripsi_singkat' => trim($parts[0] ?? ''),
                'deskripsi_lengkap' => trim($parts[1] ?? $parts[0])
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.'], 500);
        }
    }
}