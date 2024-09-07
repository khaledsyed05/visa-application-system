<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application)
    {
    }

    public function build()
    {
        return $this->subject('Visa Application Submitted')
                    ->to($this->application->email)
                    ->text('emails.application-submitted-text');
    }
}