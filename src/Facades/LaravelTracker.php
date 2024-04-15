<?php

namespace Izpixel\LaravelTracker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Izpixel\LaravelTracker\Skeleton\SkeletonClass
 */
class LaravelTracker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-tracker';
    }
}
