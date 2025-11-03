<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // <-- Gunakan HTTP Client bawaan Laravel
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi;
use App\Models\Produk;
use Carbon\Carbon;

class AiInsightController extends Controller
{
    // URL Ollama lokal (defaultnya)
    private $ollamaApiUrl = 'http://localhost:11434/api/generate';

    /**
     * Menganalisis data penjualan dan memberikan insight.
     */
    public function getInsight(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:1000',
        ]);
        $query = $validated['query'];
        
        // 1. Kumpulkan Data Penjualan
        $tokoId = Auth::user()->toko->id;
        $omsetBulanIni = Transaksi::where('toko_id', $tokoId)
            ->where('status_transaksi', 'selesai')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_harga');

        $produkTerlaris = Produk::where('toko_id', $tokoId)
            ->withCount('detailTransaksis') 
            ->orderBy('detail_transaksis_count', 'desc')
            ->take(5)
            ->get(['nama_produk', 'detail_transaksis_count']);
            
        $dataPenjualan = [
            'total_omset_bulan_ini' => $omsetBulanIni,
            'top_5_produk_terlaris' => $produkTerlaris
        ];
        $dataJson = json_encode($dataPenjualan);

        // 2. System Prompt (Peran untuk AI)
        $systemPrompt = "Anda adalah Analis Bisnis AI yang cerdas dan suportif. Anda membantu pemilik UMKM (Penjual) menganalisis data penjualan mereka.
        Berikut adalah data penjualan mereka dalam format JSON:
        $dataJson
        
        Tugas Anda adalah menjawab pertanyaan penjual berdasarkan data ini. 
        Gunakan bahasa yang memotivasi dan berikan wawasan (insight) yang actionable (bisa ditindaklanjuti).
        Contoh: 'Penjualan Anda bulan ini bagus! Produk X sangat laku, mungkin Anda bisa pertimbangkan untuk promosi produk sejenis.'
        Fokus hanya pada data yang diberikan.";
        
        $fullQuery = $systemPrompt . "\n\nPertanyaan Penjual: " . $query;


        try {
            // 3. Panggil API (HTTP Langsung ke Ollama)
            $response = Http::timeout(60) // 60 detik timeout
                ->post($this->ollamaApiUrl, [
                    'model' => 'llama3:8b', // Model yang sudah Anda download
                    'prompt' => $fullQuery,
                    'stream' => false,
                ]);

            if ($response->failed()) {
                Log::error('Error Ollama API (Insight HTTP Fail): ' . $response->body());
                return response()->json(['reply' => 'Maaf, Analis AI (Lokal) sedang mengalami gangguan.'], 500);
            }

            $reply = $response->json('response');

            return response()->json([
                'reply' => $reply ?? 'Maaf, saya tidak mengerti. Bisakah Anda bertanya dengan cara lain?'
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Insight Exception): ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, Analis AI (Lokal) tidak dapat terhubung. Pastikan Ollama sudah berjalan.'
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
        
        $prompt = "Buatkan draf deskripsi produk untuk e-commerce UMKM Kriya. 
        Nama Produk: '{$validated['nama_produk']}'
        Kategori: '{$validated['kategori']}'
        
        Tulis dalam 2 bagian:
        1.  **Deskripsi Singkat (1-2 kalimat persuasif):** 2.  **Deskripsi Lengkap (2 paragraf detail):** Jelaskan keunikan, bahan (jika bisa ditebak), dan nilai budayanya.
        
        Pisahkan jawaban dengan '---PEMISAH---'. 
        Contoh: 
        (Deskripsi singkat di sini)
        ---PEMISAH---
        (Deskripsi lengkap di sini)";
        
        try {
            // Panggil API (HTTP Langsung ke Ollama)
             $response = Http::timeout(60) // 60 detik timeout
                ->post($this->ollamaApiUrl, [
                    'model' => 'llama3:8b',
                    'prompt' => $prompt,
                    'stream' => false,
                ]);

            if ($response->failed()) {
                Log::error('Error Ollama API (Copywriting HTTP Fail): ' . $response->body());
                return response()->json(['error' => 'Gagal membuat deskripsi. Coba lagi.'], 500);
            }

            $text = $response->json('response');
            
            // Pisahkan deskripsi
            $parts = explode('---PEMISAH---', $text);
            $deskripsiSingkat = trim($parts[0] ?? 'Deskripsi singkat tidak tersedia.');
            $deskripsiLengkap = trim($parts[1] ?? 'Deskripsi lengkap tidak tersedia.');

            // Kadang AI lupa menambahkan pemisah, kita tangani di sini
            if (empty($parts[1])) {
                $deskripsiSingkat = $text; // Tampilkan saja semua jika tidak ada pemisah
            }

            return response()->json([
                'deskripsi_singkat' => $deskripsiSingkat,
                'deskripsi_lengkap' => $deskripsiLengkap
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Copywriting Exception): ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat deskripsi. Coba lagi.'], 500);
        }
    }
}

