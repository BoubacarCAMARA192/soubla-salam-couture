<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = ['client', 'tailleur', 'admin'];

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTailleur(): bool
    {
        return $this->role === 'tailleur';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function catalogModels()
    {
        return $this->hasMany(CatalogModel::class, 'created_by');
    }

    public function ordersAsTailleur()
    {
        return $this->hasMany(Order::class, 'tailleur_id');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->whereNull('read_at')->count();
    }
}
