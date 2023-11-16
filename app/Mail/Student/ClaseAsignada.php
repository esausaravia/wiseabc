<?php

namespace App\Mail\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaseAsignada extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public $classroom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Models\User $student, \App\Models\Classroom $classroom)
    {
        $this->student = $student;
        $this->classroom = $classroom;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            //from: new Address('web@wiseabcenglish.com', 'Wise ABC English'),
            //    replyTo: [new Address('taylor@example.com', 'Taylor Otwell'),],
            subject: 'Clase Asignada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.clase-asignada',
            //text: 'emails.orders.shipped-text'
            //with: ['varName'=>$varName]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(base_path('public/img/wiseabc-logo-128x.png'))->as('logo.png'),
        ];
    }
}
