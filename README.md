# Monitaroo Laravel SDK

Official Laravel SDK for [Monitaroo](https://monitaroo.com) - Logs, Metrics & Monitoring.

[![Latest Version](https://img.shields.io/packagist/v/monitaroo/monitaroo-laravel.svg)](https://packagist.org/packages/monitaroo/monitaroo-laravel)
[![PHP Version](https://img.shields.io/packagist/php-v/monitaroo/monitaroo-laravel.svg)](https://packagist.org/packages/monitaroo/monitaroo-laravel)
[![Laravel Version](https://img.shields.io/badge/laravel-9.x%20%7C%2010.x%20%7C%2011.x%20%7C%2012.x-blue.svg)](https://packagist.org/packages/monitaroo/monitaroo-laravel)
[![License](https://img.shields.io/packagist/l/monitaroo/monitaroo-laravel.svg)](https://packagist.org/packages/monitaroo/monitaroo-laravel)

## Installation

```bash
composer require monitaroo/monitaroo-laravel
```

**Requirements:** PHP 8.0+, Laravel 9+

## Configuration

Add your API key to `.env`:

```env
MONITAROO_API_KEY=mk_your_api_key
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=monitaroo-config
```

## Quick Start

```php
use Monitaroo\Laravel\Facades\Monitaroo;

// Logging
Monitaroo::info('User logged in', ['user_id' => 123]);
Monitaroo::error('Payment failed', ['order_id' => 456]);

// Metrics
Monitaroo::increment('orders.completed');
Monitaroo::gauge('queue.size', Queue::size());
Monitaroo::timing('api.response_time', 145.5);

// Timer helper
$stop = Monitaroo::startTimer('db.query');
$users = User::all();
$stop(); // Records the timing automatically
```

## Using as a Log Channel

Add Monitaroo as a log channel in `config/logging.php`:

```php
'channels' => [
    // ... other channels

    'monitaroo' => [
        'driver' => 'custom',
        'via' => \Monitaroo\Laravel\Logging\MonitarooLogger::class,
        'level' => 'debug',
    ],
],
```

Then use it:

```php
// Single channel
Log::channel('monitaroo')->info('Hello from Laravel!');

// Or add to your stack
'stack' => [
    'driver' => 'stack',
    'channels' => ['daily', 'monitaroo'],
],
```

## Configuration Options

```php
// config/monitaroo.php

return [
    // Required: Your API key
    'api_key' => env('MONITAROO_API_KEY'),

    // API endpoint (default: https://api.monitaroo.com)
    'endpoint' => env('MONITAROO_ENDPOINT', 'https://api.monitaroo.com'),

    // Service name (default: APP_NAME)
    'service' => env('MONITAROO_SERVICE', env('APP_NAME', 'laravel')),

    // Environment (default: APP_ENV)
    'environment' => env('MONITAROO_ENVIRONMENT', env('APP_ENV', 'production')),

    // Host name (auto-detected if not set)
    'host' => env('MONITAROO_HOST'),

    // Batch size before auto-flush (default: 100)
    'batch_size' => env('MONITAROO_BATCH_SIZE', 100),

    // Auto-flush on request end (default: true)
    'auto_flush' => env('MONITAROO_AUTO_FLUSH', true),
];
```

## Logging

### Log Levels

```php
Monitaroo::trace('Detailed trace');
Monitaroo::debug('Debug info');
Monitaroo::info('General info');
Monitaroo::warn('Warning');
Monitaroo::error('Error occurred');
Monitaroo::fatal('Fatal error');
```

### Context & Tags

```php
// Context becomes searchable attributes
Monitaroo::info('Order placed', [
    'order_id' => 123,
    'amount' => 99.99,
]);

// Tags are indexed for fast filtering
Monitaroo::info('Order placed', [
    'order_id' => 123,
    'tags' => [
        'type' => 'order',
        'country' => 'FR',
    ],
]);
```

### Exception Logging

```php
try {
    // ...
} catch (\Exception $e) {
    Monitaroo::error('Operation failed', [
        'exception' => $e, // Auto-extracts class, message, trace
    ]);
}
```

## Metrics

### Counter

```php
Monitaroo::increment('api.requests');
Monitaroo::increment('items.sold', 5);
Monitaroo::increment('api.requests', 1, ['endpoint' => '/users']);
```

### Gauge

```php
Monitaroo::gauge('queue.size', Queue::size());
Monitaroo::gauge('memory.mb', memory_get_usage(true) / 1024 / 1024);
```

### Timer

```php
// Manual
$start = microtime(true);
$result = doSomething();
Monitaroo::timing('operation.duration', (microtime(true) - $start) * 1000);

// Using helper
$stop = Monitaroo::startTimer('db.query', ['table' => 'users']);
$users = User::all();
$stop();
```

### Histogram

```php
Monitaroo::histogram('order.amount', $order->total);
```

## Dependency Injection

```php
use Monitaroo\Client;

class OrderController extends Controller
{
    public function __construct(
        private Client $monitaroo
    ) {}

    public function store(Request $request)
    {
        // ...
        $this->monitaroo->increment('orders.created');
        $this->monitaroo->info('Order created', ['order_id' => $order->id]);
    }
}
```

## Queue Jobs

Logs and metrics are automatically flushed at the end of each request.
For queue jobs, they're flushed when the job completes.

To manually flush (e.g., in long-running processes):

```php
Monitaroo::flush();
```

## Testing

Disable Monitaroo in tests by not setting the API key, or mock the client:

```php
// In TestCase.php
$this->mock(\Monitaroo\Client::class);
```

## License

MIT License. See [LICENSE](LICENSE) for details.
