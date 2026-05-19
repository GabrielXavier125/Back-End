<?php

namespace App\Mail;

use App\Models\EarlyRelease;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentReleasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EarlyRelease $release
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SAFE - Saída Antecipada: ' . $this->release->student->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student-released',
        );
    }
}
