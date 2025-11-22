<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingUser; // <--- PENTING: Panggil Model Sementara
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

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
                // Tetap cek ke tabel 'users' asli agar yang sudah member tidak bisa daftar lagi
                'unique:users', 
            ],

            // Validasi password super lengkap (Sesuai kode Anda)
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

        // 🔥 Generate OTP 6 digit (Sesuai kode Anda)
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // 🔥 SIMPAN KE PENDING_USERS (BUKAN USER)
        // updateOrCreate berguna jika user salah input OTP dan daftar ulang, 
        // datanya akan ditimpa (update), tidak double.
        PendingUser::updateOrCreate(
            ['email' => $request->email], // Cari berdasarkan email
            [
                'name'     => $request->name,
                'password' => Hash::make($request->password), // Password di-hash disini
                'role'     => $request->role,
                'otp'      => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]
        );

        // Kirim email ke alamat yang diinput
        Mail::to($request->email)->send(new SendOtpMail($otp));

        // Redirect ke halaman verifikasi
        return redirect()->route('verify.otp.page', [
            'email' => $request->email
        ]);
    }
}