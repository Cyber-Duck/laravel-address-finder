<?php

namespace CyberDuck\AddressFinder;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class AddressFinderServiceProvider
 *
 * @package CyberDuck\AddressFinder
 */
class AddressFinderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
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
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-address-finder.php', 'laravel-address-finder');

        // Register the service the package provides.
        $this->app->singleton('address-finder', function ($app) {
            $cached = config('laravel-address-finder.cache.enabled');
            return $cached ? app(CachedAddressFinder::class) : app(AddressFinder::class) ;
        });
    }
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-address-finder.php' => config_path('laravel-address-finder.php'),
        ], 'laravel-address-finder.config');
    }
}
