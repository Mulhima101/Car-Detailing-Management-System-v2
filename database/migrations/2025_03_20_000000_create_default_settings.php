<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up()
    {
        // Define default settings
        $defaultSettings = [
            // Company Info
            'company_name' => 'AutoX Studio',
            'company_email' => 'info@autoxstudio.com.au',
            'company_phone' => '(02) 1234 5678',
            
            // Notifications
            'notification_status_updates' => '1',
            'notification_completion_reminders' => '1',
            'notification_marketing_emails' => '0',
            
            // Reminder settings
            'reminder_days' => '3',
            
            // Email templates - Status updates
            'email_service_status_subject' => 'AutoX Studio: Service Status Update',
            'email_service_status_template' => "Hello {customer_name},\n\n{status_message}\n\nService Details:\nOrder ID: {order_id}\nVehicle: {car_brand} {car_model}\nStatus: {status}\n\nThank you for choosing AutoX Studio!",
            
            // Email templates - Reminders
            'email_service_reminder_subject' => 'AutoX Studio: Your Vehicle Will Be Ready Soon',
            'email_service_reminder_template' => "Hello {customer_name},\n\nWe wanted to let you know that your vehicle has been in our service center for a few days. We're working to complete your service as soon as possible.\n\nService Details:\nOrder ID: {order_id}\nVehicle: {car_brand} {car_model}\nStatus: {status}\nStarted: {start_date}\n\nThank you for your patience and for choosing AutoX Studio!",
            
            // Email templates - Admin alerts
            'email_admin_alert_subject' => 'AutoX Studio: Admin Service Alert',
            'email_admin_alert_template' => "Hello Admin,\n\n{alert_message}\n\nService Details:\nOrder ID: {order_id}\nCustomer: {customer_name}\nVehicle: {car_brand} {car_model}\n\nPlease review this service at your earliest convenience.",
        ];
        
        // Insert settings if they don't exist
        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
    
    public function down()
    {
        // Nothing to do here
    }
};