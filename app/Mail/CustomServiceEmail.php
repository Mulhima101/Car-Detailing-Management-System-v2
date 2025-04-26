<?php

namespace App\Mail;

use App\Models\CarService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomServiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $carService;
    public $customSubject;
    public $customMessage;
    public $logoBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(CarService $carService, string $subject, string $message)
    {
        $this->carService = $carService;
        $this->customSubject = $subject;
        $this->customMessage = $message;
        
        // Load and encode logo
        $logoPath = public_path('public/images/autox-logo.png');
        if (file_exists($logoPath)) {
            $this->logoBase64 = base64_encode(file_get_contents($logoPath));
        } else {
            $this->logoBase64 = null;
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->customSubject)
                   ->view('emails.custom-service')
                   ->with([
                       'carService' => $this->carService,
                       'customMessage' => $this->customMessage,
                       'logoBase64' => $this->logoBase64
                   ]);
    }
}