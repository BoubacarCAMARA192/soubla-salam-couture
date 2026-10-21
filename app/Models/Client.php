<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'address',
        'notes',
        'first_visit_at',
        'last_visit_at',
    ];

    protected $casts = [
        'first_visit_at' => 'date',
        'last_visit_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class)->orderBy('created_at', 'desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMeasurement(string $clothingType): ?Measurement
    {
        return $this->measurements()
            ->where('clothing_type', $clothingType)
            ->first();
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }
}
