# Laravel Tracker

Welcome to the Laravel Tracker, a powerful tracking tool designed to seamlessly integrate with your Laravel applications. This package provides comprehensive tracking capabilities, allowing you to monitor and analyze user interactions within your applications effectively.

## Features

- **Easy Integration**: Seamlessly integrates with any Laravel application with minimal configuration.
- **Data Encryption**: Supports encryption of sensitive data to protect user privacy.
- **Customizable Tracking**: Flexible configuration options allow you to specify what data to track.
- **Middleware Integration**: Includes a middleware to capture data without affecting page load times.
- **Logging and Caching**: Provides extensive logging and caching options to enhance performance and debug capabilities.
- **Exclusion Rules**: Ability to exclude specific IP addresses from being tracked.

## Installation

To install the package, run the following command in your Laravel project:

```bash
composer require izpixel/laravel-tracker
```

## Configuration

After installation, publish the configuration file with:

```bash
php artisan vendor:publish --tag=laravel-tracker::config
```
This command will publish a tracker.php configuration file to your config directory. Please refer to the config-guide.md file for detailed explanations of each configuration option.

## Usage

The Laravel Tracker automatically applies its tracking middleware to the route groups specified in the `config/tracker.php` under `route_groups`. By default, it's set to track the 'web' group, but you can adjust this setting to include any groups you wish, such as 'api'.

To add or customize middleware, you can specify your own middleware class in the `middleware` setting of the config file. If you create a custom middleware, it is crucial to invoke `LaravelTracker::collect()` within the `terminate` method of your middleware.

### Why use the terminate method?

The `terminate` method in a middleware allows you to perform tasks after the HTTP response has been sent to the client. This is particularly useful for tasks that are time-consuming and do not affect the response itself, such as logging, sending emails, or in our case, tracking. Using the `terminate` method ensures that these operations do not delay the rendering of your web page or the API response to the client, thus improving the user experience by reducing the perceived load time.

Here is an example of how you might configure and implement a custom middleware using the `terminate` method:

```php
// In your custom middleware
public function terminate(Request $request, Response $response): void
{
    \Izpixel\LaravelTracker\Facades\LaravelTracker::collect();
}
```
For more detailed information on middleware and the terminate method, please refer to the official [Laravel documentation on middleware](https://laravel.com/docs/11.x/middleware#terminable-middleware).

For more information on how to set up and customize the tracker, please see the config-guide.md.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@izpixel.com instead of using the issue tracker.

## Support

For support, please open an issue on the GitHub repository or contact our support team at opensource@izpixel.com

Thank you for using Laravel Tracker!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
