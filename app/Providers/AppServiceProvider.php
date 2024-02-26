<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        DB::listen(function(QueryExecuted $query) {
            if ($query->time > 1000) {
                logger()->channel('telegram')->debug(
                    'whenQueryingForLongerThan:' . $query->sql, $query->bindings
                );
            }
        });

        app(Kernel::class)->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(5),
            function () {
                logger()->channel('telegram')->debug(
                    'whenRequestLifecycleIsLongerThan:' . Request::url()
                );
            }
        );
    }
}
