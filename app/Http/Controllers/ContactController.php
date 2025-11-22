<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'nama'   => 'required|min:3',
            'email'  => 'required|email',
            'pesan'  => 'required|min:5',
        ]);

        try {

            // Kirim email ke inbox tujuan
            Mail::to('rupanusa.id@gmail.com') // <--- penerima aman dan tidak sama pengirim
                ->send(new ContactMail(
                    $request->nama,
                    $request->email,
                    $request->pesan
                ));

            return back()->with('success', 'Pesan Anda berhasil dikirim!');

        } catch (\Exception $e) {

            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
