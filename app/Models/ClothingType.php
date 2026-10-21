<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClothingType extends Model
{
    protected $table = 'clothing_types';

    protected $fillable = ['name', 'measurement_fields', 'is_active'];

    protected $casts = [
        'measurement_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public static function activeNames(): array
    {
        return static::where('is_active', true)->orderBy('name')->pluck('name')->all();
    }
}
