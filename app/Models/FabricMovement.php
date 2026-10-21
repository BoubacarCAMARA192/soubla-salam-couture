<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FabricMovement extends Model
{
    protected $fillable = [
        'fabric_id',
        'type',
        'quantity',
        'order_id',
        'user_id',
        'reason',
    ];

    protected $casts = [
        'quantity' => 'float',
    ];

    public function fabric()
    {
        return $this->belongsTo(Fabric::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
