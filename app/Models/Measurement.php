<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
        'client_id',
        'clothing_type_id',
        'clothing_type',
        'chest',
        'waist',
        'hips',
        'shoulders',
        'sleeve_length',
        'top_length',
        'pants_length',
        'thigh',
        'extras',
        'notes',
        'taken_by',
    ];

    protected $casts = [
        'extras' => 'array',
    ];

    protected static array $fieldLabels = [
        'chest' => 'Poitrine',
        'waist' => 'Taille',
        'hips' => 'Hanches',
        'shoulders' => 'Épaules',
        'sleeve_length' => 'Longueur de manche',
        'top_length' => 'Longueur du haut',
        'pants_length' => 'Longueur du pantalon',
        'thigh' => 'Cuisse',
    ];

    public static function fieldLabels(): array
    {
        return self::$fieldLabels;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    public function filledFields(): array
    {
        $fields = [];
        foreach (self::$fieldLabels as $key => $label) {
            $value = $this->{$key};
            if ($value !== null && (float) $value != 0.0) {
                $fields[$label] = $value.' cm';
            }
        }
        if (is_array($this->extras)) {
            foreach ($this->extras as $extra) {
                if (!empty($extra['label']) && isset($extra['value']) && $extra['value'] !== '') {
                    $fields[$extra['label']] = (float) $extra['value'].' cm';
                }
            }
        }
        return $fields;
    }
}
