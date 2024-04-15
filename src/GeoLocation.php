<?php

namespace Izpixel\LaravelTracker;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Izpixel\LaravelTracker\Traits\WithHelpers;

class GeoLocation
{
    use WithHelpers;

    public function fetch(): array
    {
        return Cache::remember($this->createUniqueCacheKey('location'), config('laravel-tracker.cache.ttl', 60 * 60), function () {
            return Http::get('http://ip-api.com/json/' . request()->ip(), [
                'fields' => '66846719',
            ])->json();
        });
    }
}
