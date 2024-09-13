<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $adminNotes;

    public function __construct(public Application $application, array $adminNotes = [])
    {
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Visa Application Status Update')
                    ->text('emails.application-status-changed')
                    ->with([
                        'application' => $this->application,
                        'adminNotes' => $this->adminNotes
                    ]);
    }
}