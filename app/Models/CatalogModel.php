<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogModel extends Model
{
    protected $table = 'catalog_models';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'type',
        'occasion',
        'suggested_fabric',
        'suggested_colors',
        'indicative_price',
        'delay_days',
        'status',
        'is_featured',
        'created_by',
    ];

    public const CATEGORIES = ['femme', 'homme', 'enfant'];

    public const TYPES = ['traditionnel', 'moderne'];

    public const OCCASIONS = ['Korité', 'Tabaski', 'mariage', 'baptême', 'cérémonie', 'ville'];

    public const STATUSES = ['draft', 'published', 'retired'];

    public const CATEGORY_LABELS = [
        'femme' => 'Femme',
        'homme' => 'Homme',
        'enfant' => 'Enfant',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function photos()
    {
        return $this->hasMany(ModelPhoto::class, 'catalog_model_id')->orderBy('position');
    }

    public function mainPhoto(): ?ModelPhoto
    {
        return $this->photos->first();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'catalog_model_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'catalog_model_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'catalog_model_id');
    }

    public function publishedReviews()
    {
        return $this->reviews()->where('status', 'published');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categoryLabelAttribute(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? $this->category;
    }

    public function averageRating(): ?float
    {
        $avg = $this->publishedReviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : null;
    }
}
