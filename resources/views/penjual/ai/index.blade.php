<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('AI Sales Insight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        Tanyakan pada AI Analyst kami tentang performa penjualan toko Anda. AI akan menganalisis data omset dan produk terlaris Anda (hanya dari pesanan yang sudah 'selesai') untuk memberikan wawasan.
                    </p>

                    <!-- Area Chat -->
                    <div id="chat-window" class="h-96 w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg p-4 overflow-y-auto space-y-4 mb-4">
                        <!-- Pesan Awal AI -->
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3">
                                <span class="text-2xl">🤖</span>
                            </div>
                            <div class="bg-indigo-100 dark:bg-indigo-900 text-gray-900 dark:text-gray-100 p-3 rounded-lg max-w-xs lg:max-w-md">
                                <p class="text-sm">Halo! Saya AI Analyst toko Anda. Apa yang ingin Anda ketahui tentang performa penjualan Anda hari ini? (Contoh: "Beri saya insight penjualan saya", "Produk apa yang paling laku?")</p>
                            </div>
                        </div>
                    </div>

                    <!-- Input Form -->
                    <form id="ai-form" class="flex items-center space-x-2">
                        @csrf {{-- CSRF Token untuk AJAX --}}
                        <x-text-input id="prompt" name="prompt" class="block w-full" placeholder="Ketik pertanyaan Anda..." required />
                        
                        <x-primary-button id="send-button">
                            {{-- SVG Icon Kirim --}}
                            <svg id="send-icon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            {{-- SVG Icon Loading (Spinner) --}}
                            <svg id="loading-icon" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </x-primary-button>
                    </form>
                    <p id="error-message" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ai-form');
            const promptInput = document.getElementById('prompt');
            const chatWindow = document.getElementById('chat-window');
            const sendButton = document.getElementById('send-button');
            const sendIcon = document.getElementById('send-icon');
            const loadingIcon = document.getElementById('loading-icon');
            const errorMessage = document.getElementById('error-message');

            // Fungsi untuk menambahkan pesan ke jendela chat
            function appendMessage(sender, message) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('flex');
                
                let content = '';
                if (sender === 'user') {
                    // Pesan pengguna (rata kanan)
                    messageDiv.classList.add('justify-end');
                    content = `
                        <div class="bg-blue-500 text-white p-3 rounded-lg max-w-xs lg:max-w-md">
                            <p class="text-sm">${message}</p>
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            <span class="text-2xl">👤</span>
                        </div>
                    `;
                } else { // sender === 'ai'
                    // Pesan AI (rata kiri)
                    content = `
                        <div class="flex-shrink-0 mr-3">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <div class="bg-indigo-100 dark:bg-indigo-900 text-gray-900 dark:text-gray-100 p-3 rounded-lg max-w-xs lg:max-w-md">
                            <p class="text-sm">${message}</p>
                        </div>
                    `;
                }
                
                messageDiv.innerHTML = content;
                chatWindow.appendChild(messageDiv);
                
                // Scroll ke bawah
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }

            // Fungsi untuk mengubah status loading
            function setLoading(isLoading) {
                if (isLoading) {
                    sendButton.disabled = true;
                    promptInput.disabled = true;
                    sendIcon.classList.add('hidden');
                    loadingIcon.classList.remove('hidden');
                } else {
                    sendButton.disabled = false;
                    promptInput.disabled = false;
                    sendIcon.classList.remove('hidden');
                    loadingIcon.classList.add('hidden');
                }
            }

            // Saat form disubmit
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const prompt = promptInput.value;
                if (!prompt) return;

                // 1. Tampilkan pesan pengguna
                appendMessage('user', prompt);
                promptInput.value = '';
                errorMessage.innerText = '';
                
                // 2. Tampilkan status loading
                setLoading(true);

                try {
                    // 3. Kirim ke backend (API kita sendiri)
                    const response = await fetch("{{ route('penjual.ai.getInsight') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ prompt: prompt })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Terjadi kesalahan saat menghubungi server.');
                    }

                    const data = await response.json();

                    // 4. Tampilkan balasan AI
                    // Ganti newline (\n) dengan <br> agar formatnya rapi
                    const formattedReply = data.reply.replace(/\n/g, '<br>');
                    appendMessage('ai', formattedReply);

                } catch (error) {
                    // 5. Tampilkan error
                    console.error('Error fetching AI insight:', error);
                    errorMessage.innerText = 'Error: ' + error.message;
                    appendMessage('ai', 'Maaf, saya sedang mengalami kesulitan. Silakan coba lagi nanti.');
                } finally {
                    // 6. Matikan loading
                    setLoading(false);
                    promptInput.focus();
                }
            });
        });
    </script>
</x-app-layout>
