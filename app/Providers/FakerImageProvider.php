<?php

namespace App\Providers;

use Faker\Provider\Base;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FakerImageProvider extends Base
{

    public function getImage(string $pathFrom, string $pathTo): string
    {
        File::makeDirectory($pathTo, $mode = 0777, true, true);
        $name = fake()->file(
            $pathFrom,
            $pathTo,
            false
        );

        $path = Str::finish(Str::start(substr($pathTo, Str::position($pathTo, 'storage')), '/'), '/');
        $path = str_replace('/app/public', '', $path);

        return $path . $name;
    }
}
