<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = [
        'name',
        'color',
        'quantity',
        'unit',
        'alert_threshold',
        'photo',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'float',
        'alert_threshold' => 'float',
    ];

    public function movements()
    {
        return $this->hasMany(FabricMovement::class)->orderBy('created_at', 'desc');
    }

    public function getIsLowAttribute(): bool
    {
        return $this->quantity <= $this->alert_threshold;
    }
}
