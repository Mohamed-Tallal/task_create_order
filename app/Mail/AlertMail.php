<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $msg  , $subsubject;
    /**
     * Create a new message instance.
     */
    public function __construct($msg , $subject)
    {
        $this->msg = $msg ;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
        ->view('emails.alert')
        ->with('msg', $this->msg);
    }
}
