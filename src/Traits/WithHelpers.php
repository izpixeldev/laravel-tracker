<?php

namespace Izpixel\LaravelTracker\Traits;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

trait WithHelpers
{
    public function createUniqueCacheKey(string $type): string
    {
        return config('laravel-tracker.cache.key') . "::$type" . "::" . request()->ip();
    }

    public function isRealVisitor(): bool
    {
        $regex = preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo|teoma|contaxe|yandex|libwww-perl|facebookexternalhit/i', $this->request->userAgent());

        return !$this->agent->isBot() && !$this->agent->isRobot() && !$regex;
    }

    public function isReserved(): bool
    {
        return in_array(request()->ip(), config('laravel-tracker.reserved', []));
    }

    public function getTableName(): string
    {
        $prefix = config('laravel-tracker.table_prefix', '');

        return $prefix != '' ? "{$prefix}_visitors" : 'visitors';
    }

    public function log(string $type, string $message, array $context = []): void
    {
        if ($this->isLoggingEnabled()) {

            if ($type == 'error') {
                $defaultContent = config('laravel-tracker.default_content', '-');

                $context = [
                    ...$context,
                    'ip' => $this->request->ip() ?? request()->ip(),
                    'session_id' => $this->request->session()->getId() ?? request()->session()->getId(),
                    'is_bot' => !$this->isRealVisitor(),
                    'is_reserved' => $this->isReserved(),
                    'language' => $this->request->getLocale() ?? $defaultContent,
                    'geolocation' => (array) $this->geo,
                    'request' => $this->request ?? request(),
                    'agent' => [
                        'languages' => $this->agent->languages() ?? $defaultContent,
                        'platform' => $this->agent->platform($this->request->userAgent()) ?? $defaultContent,
                        'platform_version' => $this->agent->version($this->agent->platform()) ?? $defaultContent,
                        'browser' => $this->agent->browser() ?? $defaultContent,
                        'browser_version' => $this->agent->version($this->agent->browser()) ?? $defaultContent,
                        'device' => $this->agent->device() ?? $defaultContent,
                        'is_mobile' => $this->geo->mobile ?? $this->agent->isMobile(),
                    ],
                    'visitor' => $this->getVisitor(),
                ];
            }

            Log::channel(config('laravel-tracker.logging.channel.name'))->$type($message, $context);
        }
    }

    public function isLoggingEnabled(): bool
    {
        return config('laravel-tracker.logging.enabled', true);
    }
}
