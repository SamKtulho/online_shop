<?php

namespace App\Models;

use Domains\Catalog\Models\Brand;
use Domains\Catalog\Models\Category;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug',
        'title',
        'brand_id',
        'price',
        'thumbnail',
        'is_on_main_page',
        'sorting'
    ];

    protected $casts = [
        'price' => PriceCast::class
    ];

    protected static function boot()
    {
        parent::boot();
    }

    protected function getThumbnailDir(): string
    {
        return 'products';
    }

    public function scopeMainPage(Builder $builder)
    {
        $builder->where('is_on_main_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
