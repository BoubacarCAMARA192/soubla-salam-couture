<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'catalog_model_id',
        'order_id',
        'author_name',
        'rating',
        'comment',
        'status',
    ];

    public const STATUSES = [
        'pending' => 'En attente',
        'published' => 'Publié',
        'rejected' => 'Refusé',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function catalogModel()
    {
        return $this->belongsTo(CatalogModel::class, 'catalog_model_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
