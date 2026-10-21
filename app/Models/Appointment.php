<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'tailleur_id',
        'order_id',
        'type',
        'scheduled_at',
        'duration_minutes',
        'status',
        'notes',
    ];

    public const TYPES = [
        'measurements' => 'Prise de mesures',
        'fitting' => 'Essayage',
        'delivery' => 'Livraison',
        'other' => 'Autre',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tailleur()
    {
        return $this->belongsTo(User::class, 'tailleur_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>=', now()->startOfDay());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }
}
