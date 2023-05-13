<?php

namespace App\Jobs;

use App\Mail\AlertMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $msg;
    protected $subject;

    /**
     * Create a new job instance.
     */
    public function __construct($email , $msg , $subject)
    {
        $this->email    = $email ;
        $this->msg      = $msg;
        $this->subject   = $subject;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new AlertMail($this->msg , $this->subject));
    }
}
