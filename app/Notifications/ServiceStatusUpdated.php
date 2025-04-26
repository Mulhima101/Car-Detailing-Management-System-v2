<?php

namespace App\Notifications;

use App\Models\CarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ServiceStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $carService;

    /**
     * Create a new notification instance.
     */
    public function __construct(CarService $carService)
    {
        Log::info("Email Is Contructed.");
        $this->carService = $carService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Check if notifications are enabled in settings
        $notificationsEnabled = config('autox.notification_status_updates', true);
        
        if (!$notificationsEnabled) {
            return [];
        }
        
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info("Mail Is created.");
        // Get custom subject from settings or use default
        $subject = config('autox.email_service_status_subject', 'AutoX Studio: Service Status Update');
        
        $statusMessages = [
            'pending' => 'Your service request has been received and is pending',
            'in-progress' => 'Great news! Work has begun on your vehicle',
            'completed' => 'Your service has been completed and your vehicle is ready!'
        ];
        
        $message = $statusMessages[$this->carService->status] ?? 'Your service status has been updated';
        
        // Get template content from settings
        $templateContent = config('autox.email_service_status_template');
        

        // Create email message
        $mail = (new MailMessage)
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
                '{status_message}' => $message
            ];
            
            // Replace placeholders with actual values
            $content = str_replace(array_keys($replacements), array_values($replacements), $templateContent);
            
            // Split into lines for email
            $lines = explode("\n", $content);
            foreach ($lines as $line) {
                if (trim($line)) {
                    $mail->line(trim($line));
                }
            }
        } else {
            // Default content if no template
            $mail->line($message)
                ->line('Service Details:')
                ->line('Order ID: ' . $this->carService->order_id)
                ->line('Vehicle: ' . $this->carService->car_brand . ' ' . $this->carService->car_model)
                ->line('License Plate: ' . $this->carService->license_plate);
        }
        
        // Add action button and footer
        $mail->action('View Service Details', url('/service/status/' . $this->carService->id))
            ->line('Thank you for choosing AutoX Studio!');
        
        Log::info("Email I Created For: " . $this->carService->id);
        
        return $mail;
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
            'status' => $this->carService->status,
            'type' => 'status_update'
        ];
    }
}