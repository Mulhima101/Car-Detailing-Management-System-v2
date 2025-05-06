<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'car_service_id',
        'title',
        'start',
        'end',
        'color',
        'description',
        'all_day'
    ];
    
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean',
    ];
    
    public function carService()
    {
        return $this->belongsTo(CarService::class);
    }
}