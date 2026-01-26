<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;


    public $otp; // خاصية لتخزين كود الـ OTP    

    public function __construct($otp)
    {
        $this->otp = $otp; // تعيين قيمة كود الـ OTP عند إنشاء الكائن   
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hello , Your OTP Code for Verification',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',    // ده الملف اللي فيه الـ HTML
            with: ['otp' => $this->otp], // مررنا الكود للـ view 
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
