<?php
declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? str($model->{static::makeSlugFrom()})->slug() . time();
        });
    }

    protected static function makeSlugFrom(): string
    {
        return 'title';
    }
}
