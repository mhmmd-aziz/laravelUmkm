<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function kontak()
    {
        return view('kontak');
    }

    public function faq()
    {
        return view('faq');
    }

    public function tentangUmkm()
    {
        return view('tentang-umkm');
    }
}
