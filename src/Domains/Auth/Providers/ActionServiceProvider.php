<?php

namespace Domains\Auth\Providers;

// use Illuminate\Support\Facades\Gate;
use Domains\Auth\Actions\RegisterNewUserAction;
use Domains\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{

    public array $bindings = [
        RegisterNewUserContract::class => RegisterNewUserAction::class
    ];
}
