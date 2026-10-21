<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'order_id',
        'client_id',
        'sender_id',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    /** Messages non lus côté atelier (tous le staff) */
    public static function unreadForWorkshop()
    {
        $staffRole = User::class;

        return Message::with(['client', 'order'])
            ->whereHas('sender', function ($q) {
                $q->where('role', 'client');
            })
            ->whereNull('read_at');
    }
}
