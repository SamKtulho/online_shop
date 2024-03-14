<?php
declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Support\Facades\File;

trait HasThumbnail
{
    abstract protected function getThumbnailDir(): string;
    public function getThumbnail(string $size, string $method = 'resize'): string
    {
        return route('thumbnail', [
            'dir' => $this->getThumbnailDir(),
            'size' => $size,
            'method' => $method,
            'file' => File::basename($this->{$this->getThumbnailField()})
        ]);
    }

    protected function getThumbnailField(): string
    {
        return 'thumbnail';
    }
}
