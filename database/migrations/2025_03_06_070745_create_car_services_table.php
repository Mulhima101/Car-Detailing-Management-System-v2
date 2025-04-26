<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    $table->string('order_id')->unique();
    $table->string('manufacturer'); // Changed from 'car_brand'
    $table->string('car_model');
    $table->string('license_plate');
    $table->string('color')->nullable();
    $table->json('services');
    $table->json('service_options')->nullable(); // Added for sub-options
    $table->text('custom_service')->nullable(); // Added for custom service description
    $table->text('notes')->nullable();
    $table->enum('status', ['pending', 'in-progress', 'completed'])->default('pending');
    $table->timestamp('start_date')->nullable();
    $table->timestamp('completion_date')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_services');
    }
};