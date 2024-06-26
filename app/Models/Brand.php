<?php

namespace App\Models;

use App\Traits\Models\HasSlug;
use App\Traits\Models\HasThumbnail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
        'is_on_main_page',
        'sorting'
    ];

    protected function getThumbnailDir(): string
    {
        return 'brands';
    }


    protected static function boot()
    {
        parent::boot();
    }

    public function scopeMainPage(Builder $builder)
    {
        $builder->where('is_on_main_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
