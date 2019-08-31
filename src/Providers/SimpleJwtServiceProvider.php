<?php

namespace LGrevelink\LaravelSimpleJWT\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use LGrevelink\LaravelSimpleJWT\Console\Commands\MakeBlueprint;
use LGrevelink\LaravelSimpleJWT\Validation\Rule\ValidJWT;

class SimpleJwtServiceProvider extends BaseServiceProvider
{
    /**
     * Boot any Simple JWT related services.
     */
    public function boot()
    {
        // Only register the commands when running from the console.
        if ($this->app->runningInConsole()) {
            $this->addCommands();
        }

        // Only register the validator rules when facades are available.
        if ($this->supportsFacades()) {
            $this->addValidatorRules();
        }
    }

    /**
     * Adds the package's commands to artisan.
     */
    protected function addCommands()
    {
        $this->commands([
            MakeBlueprint::class,
        ]);
    }

    /**
     * Extends the Validator to recognise the custom jwt rule.
     */
    protected function addValidatorRules()
    {
        Validator::extend('jwt', function ($attribute, $value, $parameters) {
            return (new ValidJWT(Arr::first($parameters)))->passes($attribute, $value);
        });
    }

    /**
     * Check if package is running on a Lumen application.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen');
    }

    /**
     * Checks whether facades are supported on the current application.
     *
     * @return bool
     */
    protected function supportsFacades()
    {
        if ($this->isLumen()) {
            return Validator::getFacadeApplication() !== null;
        }

        return true;
    }
}
