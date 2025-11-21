<!DOCTYPE html>
{{-- Ganti lang menjadi dinamis --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS CHATBOT --}}
    <style>
        #chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4f46e5;
            /* indigo-600 */
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            z-index: 9998;
        }

        #chatbot-toggle:hover {
            transform: scale(1.1);
            background-color: #4338ca;
            /* indigo-700 */
        }

        #chatbot-widget {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 450px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: scale(0.95);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 9999;
        }

        #chatbot-widget.active {
            transform: scale(1);
            opacity: 1;
            visibility: visible;
        }

        .dark #chatbot-widget {
            background-color: #1f2937;
            /* gray-800 */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        #chatbot-header {
            padding: 1rem;
            background-color: #4f46e5;
            /* indigo-600 */
            color: white;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark #chatbot-header {
            border-bottom: 1px solid #374151;
            /* gray-700 */
        }

        #chatbot-header button {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        #chatbot-messages {
            flex-grow: 1;
            padding: 1rem;
            overflow-y: auto;
            background-color: #f9fafb;
            /* gray-50 */
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .dark #chatbot-messages {
            background-color: #111827;
            /* gray-900 */
        }

        .message {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            max-width: 80%;
            line-height: 1.5;
        }

        .message.user {
            background-color: #4f46e5;
            /* indigo-600 */
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .message.bot {
            background-color: #e5e7eb;
            /* gray-200 */
            color: #1f2937;
            /* gray-800 */
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .dark .message.bot {
            background-color: #374151;
            /* gray-700 */
            color: #f3f4f6;
            /* gray-100 */
        }

        .message.loading {
            background-color: #e5e7eb;
            color: #6b7280;
            align-self: flex-start;
            font-style: italic;
        }

        .dark .message.loading {
            background-color: #374151;
            color: #9ca3af;
            /* gray-400 */
        }

        #chatbot-input {
            border-top: 1px solid #e5e7eb;
            padding: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .dark #chatbot-input {
            border-top: 1px solid #374151;
            /* gray-700 */
        }

        #chatbot-input input {
            flex-grow: 1;
            border: 1px solid #d1d5db;
            /* gray-300 */
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.875rem;
            background-color: white;
            color: #1f2937;
        }

        .dark #chatbot-input input {
            background-color: #374151;
            /* gray-700 */
            border-color: #4b5563;
            /* gray-600 */
            color: #f3f4f6;
            /* gray-100 */
        }

        #chatbot-input button {
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 1rem;
            cursor: pointer;
        }

        #chatbot-input button:disabled {
            background-color: #a5b4fc;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="font-sans antialiased" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 2000)">
    <x-loading />

    <div x-show="!loading" x-transition class="min-h-screen  dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
<main class="">
    {{ $slot }}
</main>
        <x-footer/>


    </div>

    {{-- HTML CHATBOT --}}
    <div id="chatbot-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
            class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-3.86 8.25-8.625 8.25a8.61 8.61 0 01-1.6-.225l-4.328 1.905a.86.86 0 01-1.036-.549.858.858 0 01.106-1.036l2.42-3.181A8.56 8.56 0 013 12c0-4.556 3.86-8.25 8.625-8.25S21 7.444 21 12z" />
        </svg>
    </div>
    <div id="chatbot-widget">
        <div id="chatbot-header">
            <span>{{ __('AI Assistant') }}</span> {{-- TERJEMAHKAN --}}
            <button id="chatbot-close">&times;</button>
        </div>
        <div id="chatbot-messages">
            <div class="message bot">
                {{-- TERJEMAHKAN --}}
                {{ __('Hello! I am AI Assistant. How can I help you regarding Indonesian Culture UMKM?') }}
            </div>
        </div>
        <div id="chatbot-input">
            <input type="text" id="chatbot-text-input" placeholder="{{ __('Type your message...') }}"> {{-- TERJEMAHKAN
            --}}
            <button id="chatbot-send">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.875L5.999 12zm0 0h7.5" />
                </svg>
            </button>
        </div>
    </div>
    {{-- SCRIPT CHATBOT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('chatbot-toggle');
            const widget = document.getElementById('chatbot-widget');
            const closeButton = document.getElementById('chatbot-close');
            const messagesContainer = document.getElementById('chatbot-messages');
            const textInput = document.getElementById('chatbot-text-input');
            const sendButton = document.getElementById('chatbot-send');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            toggleButton.addEventListener('click', () => {
                widget.classList.toggle('active');
            });

            closeButton.addEventListener('click', () => {
                widget.classList.remove('active');
            });

            sendButton.addEventListener('click', sendMessage);
            textInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            function sendMessage() {
                const query = textInput.value.trim();
                if (query === '') return;

                addMessage(query, 'user');
                textInput.value = '';
                sendButton.disabled = true;

                addMessage('...', 'loading');
                scrollToBottom();

                fetch('{{ route("ai.chatbot.query") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ query: query })

                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.message.loading').remove();
                        // Ganti newline (\n) dengan tag <br>
                        const formattedReply = data.reply.replace(/\n/g, '<br>');
                        addMessage(formattedReply, 'bot');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.querySelector('.message.loading').remove();
                        addMessage('Maaf, terjadi kesalahan. Coba lagi nanti.', 'bot');
                    })
                    .finally(() => {
                        sendButton.disabled = false;
                        scrollToBottom();
                    });
            }

            function addMessage(text, type) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message', type);

                messageDiv.innerHTML = text; // Gunakan innerHTML agar <br> ter-render

                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });





    </script>
    <script>
        document.addEventListener("alpine:initialized", () => {
            // Alpine sudah aktif → loading pasti ada
            setTimeout(() => {
                Alpine.store('main', {}).loading = false;
            }, 2000);
        });
    </script>

    {{-- TAMBAHAN: Script untuk Halaman (cth: AI Copywriting) --}}
    @stack('scripts')
</body>

</html>