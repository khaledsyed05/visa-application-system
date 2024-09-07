<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {recipient}';
    protected $description = 'Send a test email to the specified recipient';

    public function handle()
    {
        $recipient = $this->argument('recipient');

        $this->info("Attempting to send a test email to {$recipient}...");

        try {
            Mail::raw('This is a test email from your Laravel application.', function ($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('Test Email');
            });

            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send test email:');
            $this->error($e->getMessage());
        }
    }
}
