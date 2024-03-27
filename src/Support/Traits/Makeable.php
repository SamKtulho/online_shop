<?php
declare(strict_types=1);

namespace Support\Traits;

trait Makeable
{
    public static function make(...$args)
    {
        return new self(...$args);
    }
}
