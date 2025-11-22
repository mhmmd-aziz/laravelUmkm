<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $email;
    public $pesan;

    public function __construct($nama, $email, $pesan)
    {
        $this->nama  = $nama;
        $this->email = $email;
        $this->pesan = $pesan;
    }

    public function build()
{
    return $this->subject('Pesan Baru dari Form Kontak RupaNusa')
                ->view('emails.contact')
                ->with([
                    'nama'  => $this->nama,
                    'email' => $this->email,
                    'pesan' => $this->pesan,
                ]);
}
}
