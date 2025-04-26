<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('car_services', function (Blueprint $table) {
            $table->renameColumn('manufacturer', 'car_brand');
        });
    }
    
    public function down()
    {
        Schema::table('car_services', function (Blueprint $table) {
            $table->renameColumn('car_brand', 'manufacturer');
        });
    }
};
