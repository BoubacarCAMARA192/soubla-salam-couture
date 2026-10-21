<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'receipt_number',
        'amount',
        'method',
        'type',
        'paid_at',
        'recorded_by',
    ];

    public const METHODS = [
        'cash' => 'Espèces',
        'wave' => 'Wave',
        'orange_money' => 'Orange Money',
        'bank_transfer' => 'Virement',
    ];

    public const TYPES = [
        'deposit' => 'Acompte',
        'balance' => 'Solde',
        'extra' => 'Supplément',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function methodLabel(): string
    {
        return self::METHODS[$this->method] ?? $this->method;
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public static function generateReceiptNumber(): string
    {
        do {
            $number = 'REC-'.date('Ymd').'-'.strtoupper(\Illuminate\Support\Str::random(3));
        } while (static::where('receipt_number', $number)->exists());

        return $number;
    }
}
