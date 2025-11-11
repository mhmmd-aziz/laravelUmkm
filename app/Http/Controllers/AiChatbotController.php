<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Kita butuh Auth untuk cek role

class AiChatbotController extends Controller
{
    /**
     * Menangani query dari chatbot widget.
     */
    public function query(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:1000',
        ]);

        $query = $validated['query'];
        
        // --- INI DIA PERBAIKANNYA (PROMPT BARU) ---

        // 1. Tentukan Konteks Pengguna (Role)
        $userRole = 'Tamu'; // Default jika belum login
        if (Auth::check()) {
            $userRole = Auth::user()->role; // 'admin', 'penjual', atau 'pembeli'
        }

        // 2. Buat System Prompt (Peran AI) yang lebih canggih
        $systemPrompt = "
        Anda adalah 'Asisten AI Budaya', chatbot super untuk website e-commerce UMKM Budaya Indonesia.
        Anda 100% profesional, ramah, dan sangat membantu.

        TUGAS UTAMA ANDA:
        1.  **WAJIB DETEKSI BAHASA:** Jika pengguna bertanya dalam Bahasa Indonesia, WAJIB balas dalam Bahasa Indonesia. Jika pengguna bertanya dalam Bahasa Inggris, WAJIB balas dalam Bahasa Inggris.
        2.  **WAJIB TANPA MARKDOWN:** Balas sebagai teks biasa. JANGAN gunakan Markdown (seperti `**` atau `*`).
        3.  **SAPA PENGGUNA SESUAI ROLE:** Role pengguna saat ini adalah: '$userRole'. Sapalah mereka sesuai role jika relevan (misal: 'Sebagai $userRole, Anda bisa...').
        4.  **TAHU FITUR WEBSITE:** Anda HARUS tahu fitur-fitur website ini:
            -   **Admin:** Mengelola semua data (belum diimplementasikan).
            -   **Penjual:** Bisa 'Buat Toko', 'Tambah/Edit/Hapus Produk', melihat 'Pesanan Masuk' (dan ubah status), melihat 'Laporan Omset' (dengan grafik & ekspor Excel), dan menggunakan 'AI Insight' & 'AI Copywriting'.
            -   **Pembeli:** Bisa 'Cari Produk' (dengan filter), 'Tambah ke Keranjang', 'Checkout', mengisi 'Buku Alamat' di profil, dan melihat 'Riwayat Pesanan'.
            -   **Chatbot (Anda Sendiri):** Ada di semua halaman untuk membantu.
            -   **AI:** Menggunakan Ollama (llama3:8b) secara lokal.

        Tugas Anda adalah menjawab pertanyaan pengguna ('$query') berdasarkan konteks ini.
        ";
        // --- BATAS PERBAIKAN ---

        try {
            // 3. Panggil API Ollama (Lokal)
            $response = Http::timeout(120) // Timeout 2 menit
                ->baseUrl('http://localhost:11434/api')
                ->post('/chat', [ // Gunakan endpoint /chat
                    'model' => 'llama3:8b', // Model yang sudah Anda download
                    'stream' => false,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $query]
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Error Ollama API (Chatbot): ' . $response->body());
                return response()->json(['reply' => 'Maaf, Asisten AI sedang mengalami gangguan (Server).'], 500);
            }
            
            $reply = $response->json()['message']['content'] ?? 'Maaf, saya tidak bisa memproses balasan saat ini.';
            
            // Bersihkan balasan (hapus asterisk)
            $reply = trim(str_replace(['**', '*'], '', $reply));

            return response()->json([
                'reply' => $reply
            ]);

        } catch (\Exception $e) {
            Log::error('Error Ollama API (Chatbot): '. $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, Asisten AI sedang mengalami gangguan teknis (Tidak bisa terhubung ke Ollama). Pastikan Ollama sudah berjalan.'
            ], 500);
        }
    }
}