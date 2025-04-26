<?php

namespace App\Notifications;

use App\Models\CarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AdminServiceAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $carService;
    protected $alertType;

    /**
     * Create a new notification instance.
     */
    public function __construct(CarService $carService, string $alertType)
    {
        $this->carService = $carService;
        $this->alertType = $alertType;
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
        // Get subject from settings or use default
        $subject = config('autox.email_admin_alert_subject', 'AutoX Studio: Admin Service Alert');
        
        // Create the mail message
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello Admin,');
            
        // Set alert message based on type
        $alertMessage = "";
        switch ($this->alertType) {
            case 'new_request':
                $alertMessage = 'A new service request has been submitted.';
                break;
                
            case 'overdue':
                $alertMessage = 'A service has been in progress for more than 5 days.';
                break;
                
            default:
                $alertMessage = 'A service alert has been triggered.';
        }
        
        // Get template content from settings
        $templateContent = config('autox.email_admin_alert_template');
        
        // If we have a custom template, use it with replacements
        if ($templateContent) {
            // Prepare replacements for template
            $replacements = [
                '{alert_message}' => $alertMessage,
                '{alert_type}' => $this->alertType,
                '{order_id}' => $this->carService->order_id,
                '{customer_name}' => $this->carService->customer->name,
                '{car_brand}' => $this->carService->car_brand,
                '{car_model}' => $this->carService->car_model,
                '{status}' => ucfirst($this->carService->status),
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
            $message->line($alertMessage)
                    ->line('Order ID: ' . $this->carService->order_id)
                    ->line('Customer: ' . $this->carService->customer->name)
                    ->line('Vehicle: ' . $this->carService->car_brand . ' ' . $this->carService->car_model);
                    
            if ($this->alertType == 'overdue') {
                $message->line('Started on: ' . $this->carService->start_date->format('M d, Y'));
            }
        }
        
        // Add action button
        $message->action('View Service Details', url('/admin/service/' . $this->carService->id))
                ->line('Thank you for using AutoX Studio Management System!');
        
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
            'alert_type' => $this->alertType,
            'type' => 'admin_alert'
        ];
    }
}