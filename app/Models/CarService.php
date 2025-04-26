<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class CarService extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'order_id',
        'car_brand',
        'car_model',
        'license_plate',
        'color',
        'services',
        'notes',
        'status',
        'start_date',
        'completion_date',
        'registration_state',
    ];
    
    protected $casts = [
        'services' => 'array',
        'start_date' => 'datetime',
        'completion_date' => 'datetime',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->order_id = 'AX' . date('Ymd') . str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
            
            if (!$model->start_date && $model->status === 'in-progress') {
                $model->start_date = now();
            }
        });
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
                    ->where('data->car_service_id', $this->id);
    }
}