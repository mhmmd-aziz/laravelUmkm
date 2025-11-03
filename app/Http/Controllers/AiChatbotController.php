<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // <-- Gunakan HTTP Client bawaan Laravel
use Illuminate\Support\Facades\Log;

class AiChatbotController extends Controller
{
    // URL Ollama lokal (defaultnya)
    private $ollamaApiUrl = 'http://localhost:11434/api/generate';

    public function query(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:1000',
        ]);

        $query = $validated['query'];
        
        // 3. System Prompt (Peran untuk AI)
        $systemPrompt = "Anda adalah 'Asisten AI Budaya', chatbot customer service untuk website e-commerce UMKM Kriya & Budaya Indonesia. Misi Anda adalah membantu pengunjung dengan ramah, informatif, dan profesional. Jika ditanya tentang website ini, jelaskan bahwa ini adalah platform untuk mendukung pengrajin lokal. Jika Anda tidak tahu jawabannya, katakan Anda akan menyampaikannya ke tim support. Jaga jawaban tetap singkat dan jelas.";

        try {
            // Buat panggilan HTTP langsung ke Ollama
            $response = Http::timeout(60) // 60 detik timeout
                ->post($this->ollamaApiUrl, [
                    'model' => 'llama3:8b', // Model yang sudah Anda download
                    'prompt' => $systemPrompt . "\n\nPertanyaan: " . $query,
                    'stream' => false, // Kita tidak pakai streaming agar lebih mudah
                ]);

            if ($response->failed()) {
                Log::error('Error Ollama API (HTTP Fail): ' . $response->body());
                return response()->json(['reply' => 'Maaf, Asisten AI (Lokal) sedang mengalami gangguan.'], 500);
            }

            // Ambil jawaban dari JSON response Ollama
            $reply = $response->json('response');

            return response()->json([
                'reply' => $reply ?? 'Maaf, saya tidak mengerti. Bisakah Anda bertanya dengan cara lain?'
            ]);

        } catch (\Exception $e) {
            // Tangkap error jika API gagal (cth: Ollama tidak berjalan)
            Log::error('Error Ollama API (Exception): ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, Asisten AI (Lokal) tidak dapat terhubung. Pastikan Ollama sudah berjalan di komputer Anda.'
            ], 500);
        }
    }
}

