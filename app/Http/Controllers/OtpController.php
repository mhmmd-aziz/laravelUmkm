<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function index(Request $request)
    {
        // Jika email tidak ikut di request (misal user reload manual)
        // kita cegah error dan redirect ke halaman login/register
        if (!$request->email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session verifikasi sudah habis, silakan login ulang.'
            ]);
        }

        return view('auth.verify-otp', [
            'email' => $request->email
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        

        // --- FIX UTAMA: Bandingkan OTP sebagai STRING, bukan integer ---
        if (trim((string)$user->otp) !== trim((string)$request->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // Cek expired
        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa.']);
        }

        // Jika berhasil, verifikasi user
        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi!');
    }

    public function resend(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        // Generate OTP baru
        $newOtp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); 
        // str_pad memastikan OTP tidak hilang angka 0 di depan

        // Update user dengan OTP baru
        $user->update([
            'otp' => $newOtp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($newOtp));

        return back()->with('resent', 'Kode OTP baru telah dikirim!');
    }
}
