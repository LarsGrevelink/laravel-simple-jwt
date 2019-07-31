<?php

namespace LGrevelink\LaravelSimpleJWT\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LGrevelink\LaravelSimpleJWT\Console\Commands\MakeBlueprint;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any Simple JWT related services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeBlueprint::class,
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
    }
}
