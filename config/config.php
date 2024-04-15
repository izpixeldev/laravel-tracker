<?php

return [

    /**
     * Determines if tracking is enabled or not. By default, it is set to true.
     * This can be overridden by the TRACKER_ENABLED environment variable.
     *
     * Example usage:
     * 'enabled' => env('TRACKER_ENABLED', false) // Disables tracking
     */
    'enabled' => env('TRACKER_ENABLED', true),

    'encrypt' => [
        /**
         * Specifies whether encryption is enabled for the tracker data.
         * This can be controlled through the TRACKER_ENCRYPT environment variable.
         * By default, it is enabled (true).
         *
         * Example:
         * 'enabled' => env('TRACKER_ENCRYPT', false) // To disable encryption
         */
        'enabled' => env('TRACKER_ENCRYPT', true),

        /**
         * Defines which fields should be encrypted when data is saved.
         * This should be an array of attribute names that need encryption.
         * Only these fields will be encrypted if encryption is enabled.
         *
         * Example:
         * 'fields' => ['country', 'city'] // Encrypts only the country and steps fields
         */
        'fields' => [],
    ],

    /**
     * Lists the route groups where the tracker should be applied.
     * Default is the 'web' middleware group.
     *
     * Example usage:
     * 'route_groups' => ['web', 'api'] // Applies tracking to both web and API routes
     */
    'route_groups' => ['web'],

    /**
     * Specifies the middleware class that handles tracking logic.
     * If a custom middleware class is used, ensure to call LaravelTracker::collect()
     * within the `terminate` method of the middleware. This approach ensures that the
     * tracking logic does not affect page load times for the user, as it will run
     * after the response is sent to the client.
     * Replace `\Izpixel\LaravelTracker\Http\Middleware\TrackerMiddleware::class`
     * with any custom middleware class as necessary.
     *
     * Example usage:
     * 'middleware' => \App\Http\Middleware\CustomTrackerMiddleware::class,
     *
     * Example of implementing terminate method in your custom middleware:
     * ```php
     * public function terminate($request, $response)
     * {
     *     \LaravelTracker::collect();
     * }
     * ```
     */
    'middleware' => \Izpixel\LaravelTracker\Http\Middleware\TrackerMiddleware::class,

    /**
     * Default content to be used when no specific data can be obtained.
     * Useful for handling null values in the tracking data.
     *
     * Example usage:
     * 'default_content' => 'N/A' // Sets unknown or unavailable data to 'N/A'
     */
    'default_content' => 'Unknown',

    /**
     * Configuration settings for caching tracking data.
     * 'key' specifies the cache key under which the tracker data is stored.
     * 'ttl' defines time to live for the cache in seconds, default here is 1 hour (3600 seconds).
     *
     * Example usage:
     * 'cache' => [
     *     'key' => 'custom_cache_key',
     *     'ttl' => 120 * 60 // Cache duration set to 2 hours
     * ],
     */
    'cache' => [
        'key' => 'laravel-tracker',
        'ttl' => 60 * 60
    ],

    /**
     * Defines logging configurations for the tracker.
     * - 'enabled' toggles logging on or off.
     * - 'channel' configures the specifics of the logging channel, including:
     *   - 'name': The identifier for the log channel.
     *   - 'driver': The logging method, set to 'single' for single file logging.
     *   - 'path': The file path where logs are stored.
     *   - 'level': The minimum logging level at which logs are written, defaults to 'debug'.
     *   - 'replace_placeholders': Whether to replace placeholders in log messages.
     */
    'logging' => [
        'enabled' => true,
        'channel' => [
            'name' => 'laravel_tracker',
            'driver' => 'single',
            'path' => storage_path('logs/laravel-tracker.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],
    ],

    /**
     * Specifies a prefix for database tables related to tracking.
     * This is useful to avoid table name collisions in the database.
     *
     * Example usage:
     * 'table_prefix' => 'tracking' // Prefixes all tracker-related tables with 'tracking'
     */
    'table_prefix' => '',

    /**
     * Lists IP addresses that should be excluded from tracking.
     * Common use is to exclude local development addresses.
     *
     * Example usage:
     * 'reserved' => [
     *     '127.0.0.1',
     *     '::1',
     *     '192.168.1.1'
     * ], // Adds 192.168.1.1 to the list of reserved IPs
     */
    'reserved' => [
//        '127.0.0.1',
//        '::1',
    ],

];
