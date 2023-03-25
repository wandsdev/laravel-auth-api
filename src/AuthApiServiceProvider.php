<?php

namespace WandsDev\AuthApi;

use Illuminate\Support\ServiceProvider;

class AuthApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wandsdev');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'wandsdev');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth-api.php', 'auth-api');

        // Register the service the package provides.
        $this->app->singleton('auth-api', function ($app) {
            return new AuthApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth-api'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/auth-api.php' => config_path('auth-api.php'),
        ], 'auth-api.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/wandsdev'),
        ], 'auth-api.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/wandsdev'),
        ], 'auth-api.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/wandsdev'),
        ], 'auth-api.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
