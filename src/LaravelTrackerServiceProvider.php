<?php

namespace Izpixel\LaravelTracker;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Izpixel\LaravelTracker\Http\Middleware\TrackerMiddleware;

class LaravelTrackerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Kernel $kernel)
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-tracker.php'),
            ], 'laravel-tracker::config');

            // $this->commands([]);
        }

        $this->appendMiddlewareToGroups($kernel);
        $this->addLoggingConfigurations();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-tracker');

        $this->app->singleton('laravel-tracker', function () {
            return new LaravelTracker;
        });
    }

    public function appendMiddlewareToGroups(Kernel $kernel): void
    {
        $groups = config('laravel-tracker.route_groups', ['web']);
        $middleware = config('laravel-tracker.middleware', TrackerMiddleware::class);

        foreach ($groups as $group) {
            $kernel->appendMiddlewareToGroup($group, $middleware);
        }
    }

    public function addLoggingConfigurations(): void
    {
        $this->app->make('config')->set('logging.channels.' . config('laravel-tracker.logging.channel.name', 'laravel_tracker'), config('laravel-tracker.logging.channel'));
    }
}
