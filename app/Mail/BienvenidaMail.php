<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $email;
    public string $password;

    public function __construct(string $nombre, string $email, string $password)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a IT-Secure!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bienvenido',
        );
    }
}
