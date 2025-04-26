<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        $settings = [
            ['key' => 'company_name', 'value' => 'AutoX Studio'],
            ['key' => 'company_email', 'value' => 'info@autoxstudio.com'],
            ['key' => 'company_phone', 'value' => '(02) 1234 5678'],
            ['key' => 'reminder_days', 'value' => '3'],
            ['key' => 'notification_status_updates', 'value' => '1'],
            ['key' => 'notification_completion_reminders', 'value' => '1'],
            ['key' => 'notification_marketing_emails', 'value' => '0'],
        ];
        
        DB::table('settings')->insert($settings);
    }
    
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};