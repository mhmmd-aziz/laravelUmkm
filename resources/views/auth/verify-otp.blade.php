<x-guest-layout>

<div class="min-h-screen flex justify-center items-center bg-gray-100">

    <div class="max-w-md w-full bg-white shadow rounded-lg p-6">

        <h2 class="text-xl font-bold mb-3">Verifikasi Email</h2>
        <p class="text-sm text-gray-600 mb-4">
            Kami telah mengirim kode OTP ke <strong>{{ $email }}</strong>.
        </p>

        {{-- SweetAlert: resent (berhasil kirim ulang) --}}
        @if (session('resent'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kode Baru Terkirim',
                        text: '{{ session('resent') }}',
                        confirmButtonColor: '#48bb78'
                    });
                });
            </script>
        @endif

        {{-- SweetAlert: error --}}
        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        html: `
                            <ul style="text-align:left;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `,
                        confirmButtonColor: '#e53e3e'
                    });
                });
            </script>
        @endif

        {{-- FORM VERIFIKASI (Menggunakan 6 kotak input) --}}
        <form id="otp-form" action="{{ route('verify.otp') }}" method="POST" class="mb-4">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" id="otp-hidden">

            <label class="block text-sm font-medium mb-2">Masukkan Kode OTP</label>

            <div id="otp-inputs" class="flex gap-2 justify-center mb-3">
                {{-- 6 kotak --}}
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
                <input inputmode="numeric" pattern="[0-9]*" maxlength="1" class="otp-box w-12 h-12 text-center border rounded-lg text-lg" />
            </div>

            @error('otp')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button type="submit" id="verify-btn" class="w-full mt-3 bg-orange-500 text-white py-2 rounded hover:bg-orange-600">
                Verifikasi
            </button>
        </form>

        {{-- Form resend --}}
        <div class="text-center">
            <form id="resend-form" action="{{ route('verify.otp.resend') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button id="resend-btn" type="submit" class="text-sm text-orange-600 hover:underline">
                    Kirim Ulang Kode OTP
                </button>
            </form>
            <p id="resend-timer" class="text-xs text-gray-500 mt-2"></p>
        </div>

    </div>

</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- OTP JS: six-digit UI, paste handling, countdown resend -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const boxes = document.querySelectorAll('.otp-box');
    const hiddenOtp = document.getElementById('otp-hidden');
    const form = document.getElementById('otp-form');
    const resendBtn = document.getElementById('resend-btn');
    const resendTimer = document.getElementById('resend-timer');

    // Fokus ke kotak pertama
    if (boxes.length) boxes[0].focus();

    // Move focus and handle backspace
    boxes.forEach((box, idx) => {
        box.addEventListener('input', (e) => {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val;

            if (val && idx < boxes.length - 1) {
                boxes[idx + 1].focus();
            }

            // If last box filled, optionally auto-submit (disabled here)
        });

        box.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && idx > 0) {
                boxes[idx - 1].focus();
            }

            // Left / Right arrow nav
            if (e.key === 'ArrowLeft' && idx > 0) boxes[idx - 1].focus();
            if (e.key === 'ArrowRight' && idx < boxes.length - 1) boxes[idx + 1].focus();
        });

        // Paste handler: support pasting full 6-digit code into any box
        box.addEventListener('paste', (e) => {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/\D/g, '').slice(0, boxes.length).split('');
            if (digits.length) {
                e.preventDefault();
                boxes.forEach((b, i) => {
                    b.value = digits[i] || '';
                });
                // focus last filled
                const lastFilled = Math.min(digits.length - 1, boxes.length -1);
                boxes[lastFilled].focus();
            }
        });
    });

    // On submit, combine inputs into hidden field
    form.addEventListener('submit', (e) => {
        const code = Array.from(boxes).map(b => b.value.trim()).join('');
        if (code.length !== boxes.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Kode tidak lengkap',
                text: 'Masukkan 6 digit kode OTP.',
                confirmButtonColor: '#e53e3e'
            });
            return;
        }
        hiddenOtp.value = code;
    });

    // Resend countdown: disable for 30 seconds on page load
    let countdown = 30;
    function startCountdown(seconds) {
        countdown = seconds;
        resendBtn.setAttribute('disabled', 'disabled');
        resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
        updateTimer();

        const interval = setInterval(() => {
            countdown--;
            updateTimer();
            if (countdown <= 0) {
                clearInterval(interval);
                resendBtn.removeAttribute('disabled');
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                resendTimer.textContent = '';
            }
        }, 1000);
    }

    function updateTimer() {
        resendTimer.textContent = `Kirim ulang tersedia dalam ${countdown} detik.`;
    }

    // Start countdown on page load to prevent abuse
    startCountdown(30);

    // Optional: hook into resend form to restart countdown after clicking
    const resendForm = document.getElementById('resend-form');
    resendForm.addEventListener('submit', function (e) {
        // Allow submit to go to server; but start client countdown immediately
        startCountdown(30);
    });

    // Autofill if server redirects with previous old input (optional)
    @if(old('otp'))
        (function fillOld(){
            const oldOtp = "{{ old('otp') }}";
            if (oldOtp.length === boxes.length) {
                boxes.forEach((b, i) => b.value = oldOtp[i]);
            }
        })();
    @endif
});
</script>

<style>
    /* Minor styling for OTP boxes (can adjust to taste) */
    .otp-box {
        -moz-appearance: textfield;
        text-align: center;
        letter-spacing: 6px;
    }
    .otp-box::-webkit-outer-spin-button,
    .otp-box::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

</x-guest-layout>
