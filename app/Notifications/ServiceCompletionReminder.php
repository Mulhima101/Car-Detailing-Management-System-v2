<?php

namespace App\Notifications;

use App\Models\CarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ServiceCompletionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $carService;

    /**
     * Create a new notification instance.
     */
    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Get email subject and template from settings, or use defaults
        $subject = config('autox.email_service_reminder_subject', 'AutoX Studio: Your Vehicle Will Be Ready Soon');
        $templateContent = config('autox.email_service_reminder_template');
        
        // Build the email
        $message = (new MailMessage)
                    ->subject($subject)
                    ->greeting('Hello ' . $notifiable->name . ',');
        
        // If we have a custom template, use it with replacements
        if ($templateContent) {
            // Prepare replacements for template
            $replacements = [
                '{customer_name}' => $notifiable->name,
                '{order_id}' => $this->carService->order_id,
                '{car_brand}' => $this->carService->car_brand,
                '{car_model}' => $this->carService->car_model,
                '{status}' => ucfirst($this->carService->status),
                '{start_date}' => $this->carService->start_date ? $this->carService->start_date->format('M d, Y') : 'Not started',
            ];
            
            // Replace placeholders with actual values
            $content = str_replace(array_keys($replacements), array_values($replacements), $templateContent);
            
            // Split into lines for email
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (trim($line)) {
                    $message->line(trim($line));
                }
            }
        } else {
            // Default content if no template
            $message->line('We wanted to let you know that your vehicle is scheduled to be ready soon.')
                    ->line('Service Details:')
                    ->line('Order ID: ' . $this->carService->order_id)
                    ->line('Vehicle: ' . $this->carService->car_brand . ' ' . $this->carService->car_model)
                    ->line('Status: ' . ucfirst($this->carService->status));
        }
        
        // Add action button and footer
        $message->action('View Service Details', url('/service/status/' . $this->carService->id))
                ->line('Thank you for choosing AutoX Studio!');
        
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'car_service_id' => $this->carService->id,
            'order_id' => $this->carService->order_id,
            'type' => 'reminder'
        ];
    }
}