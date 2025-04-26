<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmailSystem extends Command
{
    protected $signature = 'autox:test-email {recipient? : Email address to send test email to}';
    protected $description = 'Test email functionality';

    public function handle()
    {
        $recipient = $this->argument('recipient') ?: 'redpostworks@gmail.com';
        
        $this->info('Sending test email to ' . $recipient . '...');

        try {
            Mail::raw('This is a test email from AutoX Service System sent at ' . now(), function (Message $message) use ($recipient) {
                $message->to($recipient)
                    ->subject('AutoX Service - Test Email');
            });

            $this->info('Test email sent successfully! Please check your inbox.');
        } catch (\Exception $e) {
            $this->error('Error sending test email: ' . $e->getMessage());
        }
    }
}