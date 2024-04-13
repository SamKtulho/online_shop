<?php
declare(strict_types=1);

namespace Support\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        $slug = str($this->{$this->makeSlugFrom()})->slug()->value();

        if (($newId = $this->getUniqueSlugId($slug)) > 0) {
            $slug .= '-' . $newId;
        }

        $this->{$this->slugColumn()} = $this->slug ?? $slug;
    }

    protected function makeSlugFrom(): string
    {
        return 'title';
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    private function getUniqueSlugId(string $slug): int
    {
        $incrementNumber = 0;
        $slugModel = $this->newQuery()
            ->where($this->slugColumn(),'LIKE',"{$slug}%")
            ->latest()
            ->first();

        if ($slugModel) {
            $incrementNumber++;
            if ($slugModel->slugColumn() !== $slug) {
                $lastNumber = (int) Str::replaceStart($slug . '-', '', $slugModel->slugColumn());
                $incrementNumber = $lastNumber ? $lastNumber++ : $incrementNumber;
            }
        }

        return $incrementNumber;
    }
}
