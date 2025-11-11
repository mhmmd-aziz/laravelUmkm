<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('AI Sales Insight') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"> {{-- Perkecil max-width agar mirip chat --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Tanyakan pada AI Analyst kami tentang performa penjualan toko Anda. AI akan menganalisis data omset dan produk terlaris Anda (hanya dari pesanan yang sudah 'selesai') untuk memberikan wawasan.
                    </p>

                    {{-- Ini adalah UI Chatbot baru --}}
                    <div id="ai-insight-widget" class="flex flex-col h-[60vh]"> {{-- Tinggi 60% viewport --}}
                        
                        <!-- Messages -->
                        <div id="ai-insight-messages" class="flex-grow p-4 space-y-3 overflow-y-auto bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <!-- Pesan Awal -->
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-600 text-white font-semibold">
                                    🤖
                                </span>
                                <div class="p-3 bg-gray-200 dark:bg-gray-700 rounded-lg rounded-tl-none shadow-sm">
                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                        Halo! Saya AI Analyst toko Anda. Apa yang ingin Anda ketahui tentang performa penjualan Anda hari ini? (Contoh: "Beri saya insight penjualan saya", "Produk apa yang paling laku?")
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Input -->
                        <div id="ai-insight-input" class="mt-4 flex gap-2">
                            <input type="text" id="ai-insight-text-input" placeholder="Ketik pertanyaan Anda..." class="flex-grow block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <x-primary-button id="ai-insight-send">
                                <!-- Icon Send (SVG) -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.875L5.999 12zm0 0h7.5" />
                                </svg>
                            </x-primary-button>
                        </div>
                        <p id="ai-insight-error" class="mt-2 text-sm text-red-600 dark:text-red-400" style="display: none;"></p>

                    </div>
                    {{-- Batas UI Chatbot baru --}}

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const messagesContainer = document.getElementById('ai-insight-messages');
            const textInput = document.getElementById('ai-insight-text-input');
            const sendButton = document.getElementById('ai-insight-send');
            const errorText = document.getElementById('ai-insight-error');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fungsi untuk scroll ke bawah
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Fungsi untuk menambah pesan ke UI
            function addMessage(text, role) {
                const messageWrapper = document.createElement('div');
                const messageBubble = document.createElement('div');
                
                // Ganti \n (new line) menjadi tag <br> agar tampil di HTML
                text = text.replace(/\n/g, '<br>');

                messageBubble.innerHTML = text; // Gunakan innerHTML agar <br> ter-render
                messageBubble.classList.add('p-3', 'rounded-lg', 'shadow-sm', 'text-sm');

                if (role === 'user') {
                    messageWrapper.classList.add('flex', 'items-start', 'gap-3', 'justify-end');
                    messageBubble.classList.add('bg-indigo-600', 'text-white', 'rounded-br-none');
                    messageWrapper.innerHTML = `
                        ${messageBubble.outerHTML}
                        <span class="flex-shrink-0 inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-700 text-white font-semibold">
                            👤
                        </span>
                    `;
                } else { // bot atau loading
                    messageWrapper.classList.add('flex', 'items-start', 'gap-3');
                    messageBubble.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100', 'rounded-tl-none');
                    if (role === 'loading') {
                        messageBubble.classList.add('italic');
                    }
                     messageWrapper.innerHTML = `
                        <span class="flex-shrink-0 inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-600 text-white font-semibold">
                            🤖
                        </span>
                        ${messageBubble.outerHTML}
                    `;
                }
                
                messagesContainer.appendChild(messageWrapper);
                scrollToBottom();
            }

            // Fungsi untuk kirim pesan
            async function sendMessage() {
                const query = textInput.value.trim();
                if (query === '') return;

                errorText.style.display = 'none'; // Sembunyikan error lama
                addMessage(query, 'user');
                textInput.value = '';
                sendButton.disabled = true;

                // Tampilkan status loading
                addMessage('...', 'loading');

                try {
                    const response = await fetch('{{ route("penjual.ai.getInsight") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ prompt: query }) // Kirim sebagai 'prompt'
                    });

                    // Hapus status loading
                    messagesContainer.querySelector('.flex:has(.loading)')?.remove();

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.reply || `HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    addMessage(data.reply, 'bot');

                } catch (error) {
                    console.error('Error:', error);
                    // Hapus status loading jika masih ada
                    messagesContainer.querySelector('.flex:has(.loading)')?.remove();
                    // Tampilkan error di bawah input
                    errorText.textContent = `Error: ${error.message || 'Terjadi kesalahan saat menghubungi server.'}`;
                    errorText.style.display = 'block';
                } finally {
                    sendButton.disabled = false;
                    textInput.focus();
                }
            }
            
            sendButton.addEventListener('click', sendMessage);
            textInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>