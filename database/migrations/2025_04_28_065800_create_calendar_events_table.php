<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventsTable extends Migration
{
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_service_id');
            $table->string('title');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->boolean('all_day')->default(false);
            $table->timestamps();
            
            $table->foreign('car_service_id')->references('id')->on('car_services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
}