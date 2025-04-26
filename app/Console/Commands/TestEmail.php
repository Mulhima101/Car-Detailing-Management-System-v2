<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmail extends Command
{
    protected $signature = 'autox:test-email {recipient=redpostworks@gmail.com}';
    protected $description = 'Test email functionality';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        $this->info('Sending test email to ' . $recipient . '...');

        try {
            Mail::raw('This is a test email from AutoX Service System at ' . now(), function (Message $message) use ($recipient) {
                $message->to($recipient)
                    ->subject('AutoX Service - Test Email');
            });

            $this->info('Test email sent successfully! Please check your inbox.');
        } catch (\Exception $e) {
            $this->error('Error sending test email: ' . $e->getMessage());
        }
    }
}