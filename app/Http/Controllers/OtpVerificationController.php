<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;           // Model User Asli
use App\Models\PendingUser;    // Model User Sementara
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;      // Pastikan Mailable ini ada

class OtpVerificationController extends Controller
{
    /**
     * Tampilkan Halaman Input OTP
     */
    public function show(Request $request)
    {
        // Kita tangkap email dari URL agar user tidak perlu ketik ulang
        return view('auth.verify-otp', [
            'email' => $request->query('email')
        ]);
    }

    /**
     * Proses Cek OTP dan Pindahkan Data
     */
    public function verify(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6', // Pastikan 6 digit
        ]);

        // 2. Cari data di tabel PENDING (Ruang Tunggu)
        $pendingUser = PendingUser::where('email', $request->email)
                        ->where('otp', $request->otp) // Cocokkan OTP
                        ->first();

        // 3. Cek apakah User ketemu DAN OTP belum kadaluarsa
        if ($pendingUser && now()->lessThan($pendingUser->otp_expires_at)) {
            
            // --- PINDAHKAN DATA KE TABEL ASLI (USERS) ---
            $newUser = User::create([
                'name'              => $pendingUser->name,
                'email'             => $pendingUser->email,
                'password'          => $pendingUser->password, // Password sudah di-hash di awal
                'role'              => $pendingUser->role,     // Pastikan kolom role ada
                'email_verified_at' => now(),                  // Otomatis verified
            ]);

            // 4. Hapus data dari tabel Pending (Bersih-bersih)
            $pendingUser->delete();

            // 5. Login-kan User Baru
            Auth::login($newUser);

            // 6. Masuk Dashboard
            return redirect()->route('dashboard');
        }

        // Jika Salah atau Expired
        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
    }

    /**
     * Fitur Kirim Ulang OTP (Resend)
     */
    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cari user di ruang tunggu
        $pendingUser = PendingUser::where('email', $request->email)->first();

        // Jika user ada
        if ($pendingUser) {
            // Generate OTP Baru
            $newOtp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Update Database
            $pendingUser->update([
                'otp' => $newOtp,
                'otp_expires_at' => now()->addMinutes(10)
            ]);

            // Kirim Email Lagi
            // Pastikan SendOtpMail sudah dibuat. Jika belum, komentar baris ini dulu.
            Mail::to($pendingUser->email)->send(new SendOtpMail($newOtp));

            // Kembali dengan pesan sukses
            return back()->with('resent', 'Kode OTP baru telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => 'Email tidak ditemukan atau sudah terverifikasi.']);
    }
}