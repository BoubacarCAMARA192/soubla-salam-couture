<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPhoto extends Model
{
    protected $fillable = [
        'catalog_model_id',
        'path',
        'alt',
        'position',
    ];

    public function catalogModel()
    {
        return $this->belongsTo(CatalogModel::class, 'catalog_model_id');
    }
}
