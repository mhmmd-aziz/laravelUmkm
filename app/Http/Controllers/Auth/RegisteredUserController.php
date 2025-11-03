<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Import Rule

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Validasi untuk role
            // Memastikan role yang dikirim adalah 'pembeli' atau 'penjual'
            // Admin tidak boleh mendaftar dari sini
            'role' => ['required', 'string', Rule::in(['pembeli', 'penjual'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Simpan role ke database
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Arahkan pengguna berdasarkan role setelah registrasi
        // Nanti kita akan buat middleware untuk ini, sekarang redirect ke dashboard saja
        if ($user->role === 'penjual') {
            // Nanti bisa diarahkan ke 'penjual.dashboard'
            return redirect(route('dashboard', absolute: false)); 
        }

        // Default redirect untuk pembeli
        return redirect(route('dashboard', absolute: false));
    }
}
