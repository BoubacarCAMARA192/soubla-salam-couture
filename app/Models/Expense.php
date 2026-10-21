<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'label',
        'category',
        'amount',
        'spent_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'float',
        'spent_at' => 'date',
    ];
}
