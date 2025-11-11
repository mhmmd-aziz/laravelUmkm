<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Mengganti bahasa (locale) aplikasi.
     */
    public function switchLang(Request $request): RedirectResponse
    {
        // 1. Validasi bahasa yang di-request (hanya 'id' atau 'en')
        $validated = $request->validate([
            'locale' => 'required|string|in:id,en',
        ]);

        $locale = $validated['locale'];

        // 2. Simpan bahasa yang dipilih ke dalam session
        Session::put('locale', $locale);

        // 3. Set locale aplikasi untuk request saat ini
        App::setLocale($locale);

        // 4. Kembali ke halaman sebelumnya
        return redirect()->back();
    }
}