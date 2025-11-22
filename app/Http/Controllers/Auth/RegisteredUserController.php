<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users',
            ],

            // Validasi password super lengkap
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],

            'role' => ['required', 'string', Rule::in(['pembeli', 'penjual'])],
        ]);

        // Buat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // 🔥 Generate OTP 6 digit DAN pastikan tidak hilang angka 0 depan
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP ke database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        \Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));

        // 🔥 Jangan login dulu — user harus verifikasi OTP dulu
        return redirect()->route('verify.otp.page', [
            'email' => $user->email
        ]);
    }
}
