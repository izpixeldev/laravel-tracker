<?php

namespace Izpixel\LaravelTracker;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Izpixel\LaravelTracker\Models\Visitor;
use Izpixel\LaravelTracker\Traits\WithHelpers;
use Jenssegers\Agent\Agent;

/**
 *
 */
class LaravelTracker
{
    use WithHelpers;

    /**
     * @var Agent
     */
    public Agent $agent;

    /**
     * @var Request|array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed|string|null
     */
    public Request $request;
    /**
     * @var Visitor|null
     */
    public Visitor|null $visitor;
    /**
     * @var object
     */
    public object $geo;

    /**
     * @var array
     */
    public array $data;

    /**
     *
     */
    public function __construct()
    {
        if (config('laravel-tracker.enabled', true)) {
            $this->request = request();
            $this->agent = new Agent();
            $this->visitor = $this->getVisitor();
            $this->geo = (object) (new GeoLocation())->fetch();
            $this->data = $this->createDataArray();
        } else {
            die();
        }
    }

    /**
     * @return void
     */
    public function collect(): void
    {
        if (config('laravel-tracker.enabled', true)) {
            $this->save();
        }
    }

    /**
     * @return void
     */
    private function save(): void
    {
        try {
            if ($this->isRealVisitor() && !$this->isReserved()) {
                if (!$this->visitor) {
                    Visitor::create([
                        ...$this->data,
                        'steps' => [],
                    ]);
                } else {
                    $steps = $this->visitor->steps ?? [];
                    $steps[] = $this->data;

                    $this->visitor->update([
                        'steps' => $steps,
                        'updated_at' => now(),
                    ]);

                    Cache::forget($this->createUniqueCacheKey('visitor'));
                }

                $this->log('info', __METHOD__, $this->data);
            }
        }catch (\Exception $exception) {
            $this->log('error', $exception->getMessage());
        }
    }

    /**
     * @return Visitor|null
     */
    public function getVisitor(): Visitor|null
    {
        return Cache::remember($this->createUniqueCacheKey('visitor'), config('laravel-tracker.cache.ttl'), function () {
            return Visitor::where('ip_address', $this->request->ip())->first();
        });
    }

    /**
     * @return array
     */
    private function createDataArray(): array
    {
        $defaultContent = config('laravel-tracker.default_content', '-');

        parse_str($this->request->getQueryString(), $queries);

        return [
            'ip_address' => $this->request->ip(),
            'referer' => $this->request->headers->get('referer'),
            'url' => $this->request->fullUrl(),
            'queries' => $queries,
            'continent' => $this->geo->continent ?? $defaultContent,
            'continent_code' => $this->geo->continentCode ?? $defaultContent,
            'country' => $this->geo->country ?? $defaultContent,
            'country_code' => $this->geo->countryCode ?? $defaultContent,
            'region' => $this->geo->region ?? $defaultContent,
            'region_name' => $this->geo->regionName ?? $defaultContent,
            'district' => $this->geo->district ?? $defaultContent,
            'longitude' => $this->geo->lon ?? $defaultContent,
            'offset' => $this->geo->offset ?? $defaultContent,
            'latitude' => $this->geo->lat ?? $defaultContent,
            'timezone' => $this->geo->timezone ?? $defaultContent,
            'currency' => $this->geo->currency ?? $defaultContent,
            'language' => $this->request->getLocale() ?? $defaultContent,
            'languages' => $this->agent->languages() ?? $defaultContent,
            'user_agent' => $this->request->userAgent() ?? $defaultContent,
            'platform' => $this->agent->platform($this->request->userAgent()) ?? $defaultContent,
            'platform_version' => $this->agent->version($this->agent->platform()) ?? $defaultContent,
            'browser' => $this->agent->browser() ?? $defaultContent,
            'browser_version' => $this->agent->version($this->agent->browser()) ?? $defaultContent,
            'device' => $this->agent->device() ?? $defaultContent,
            'isp' => $this->geo->isp ?? $defaultContent,
            'org' => $this->geo->org ?? $defaultContent,
            'as' => $this->geo->as ?? $defaultContent,
            'as_name' => $this->geo->asname ?? $defaultContent,
            'reverse' => $this->geo->reverse ?? $defaultContent,
            'is_mobile' => $this->geo->mobile ?? $this->agent->isMobile(),
            'is_proxy' => $this->geo->proxy ?? false,
            'is_hosting' => $this->geo->hosting ?? false,
            'created_at' => now(),
        ];
    }
}
