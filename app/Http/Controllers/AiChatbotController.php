<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AiChatbotController extends Controller
{
    public function query(Request $request)
    {
        $validated = $request->validate(['query' => 'required|string|max:1000']);
        $query = $validated['query'];
        
        // Cek Role User
        $userRole = Auth::check() ? Auth::user()->role : 'Pengunjung Tamu';
        $userName = Auth::check() ? Auth::user()->name : 'Kak';

        // --- IDENTITY & KNOWLEDGE BASE ---
        $systemPrompt = "
        IDENTITAS:
        Anda adalah 'Asisten AI Rupa Nusantara', chatbot resmi untuk platform e-commerce budaya Indonesia.
        Platform ini dibuat dan dikembangkan oleh: Tim Out of The Box : yaitu 
        Muhammad Aziz (ketua)
        Amirullah (anngota)
        Deswita Nazwa Ariani (anngota)
        Mirza Feberani (anngota)
        Muhammad Alif Arrayyan (anngota)
        Kami Berasal Dari Kampus Politeknik Negeri Lhokseumawe.
        
        PENGETAHUAN WEBSITE:
        - Rupa Nusantara adalah wadah untuk produk budaya lokal (Batik, Anyaman, Ukiran, dll).
        - Fitur Penjual: Bisa kelola toko, upload produk, dan cek omset dengan AI Insight.
        - Fitur Pembeli: Bisa belanja, checkout aman, dan tracking order.
        - User saat ini: '$userName' (Role: $userRole).

        ATURAN JAWABAN:
        1. Jawab ramah dalam Bahasa Indonesia.
        2. JANGAN gunakan Markdown (teks biasa saja).
        3. Jika ditanya siapa pembuat website, jawab: 'Platform ini dibuat dan dikembangkan oleh: Tim Out of The Box : yaitu 
        Muhammad Aziz (ketua)
        Amirullah (anngota)
        Deswita Nazwa Ariani (anngota)
        Mirza Feberani (anngota)
        Muhammad Alif Arrayyan (anngota)
        Kami Berasal Dari Kampus Politeknik Negeri Lhokseumawe..
        4. Jawab singkat dan to the point.
        ";

        try {
            $apiKey = env('GROQ_API_KEY');
            $model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query]
                ],
                'temperature' => 0.5,
                'max_tokens' => 300,
            ]);

            if (!$response->successful()) {
                Log::error('Groq Chatbot Error: ' . $response->body());
                return response()->json(['reply' => 'Maaf, Asisten sedang sibuk.'], 500);
            }
            
            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Error.';
            $reply = trim(str_replace(['**', '*'], '', $reply));

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('Groq Exception: '. $e->getMessage());
            return response()->json(['reply' => 'Gangguan koneksi.'], 500);
        }
    }
}