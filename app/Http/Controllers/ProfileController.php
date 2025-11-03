<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
// --- TAMBAHAN BARU ---
use App\Models\Alamat; // Panggil model Alamat
// --- BATAS TAMBAHAN ---

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // --- TAMBAHAN LOGIKA ---
        $user = $request->user();
        $alamats = collect(); // Buat koleksi kosong

        // Hanya ambil data alamat jika user adalah 'pembeli'
        if ($user->role === 'pembeli') {
            $alamats = Alamat::where('user_id', $user->id)
                             ->orderBy('label_alamat', 'asc')
                             ->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'alamats' => $alamats, // Kirim data alamat ke view
        ]);
        // --- BATAS TAMBAHAN ---
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
