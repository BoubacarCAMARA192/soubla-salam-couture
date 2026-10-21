<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'catalog_model_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function catalogModel()
    {
        return $this->belongsTo(CatalogModel::class, 'catalog_model_id');
    }
}
