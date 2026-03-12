<?php

declare(strict_types=1);

namespace Monitaroo\Laravel;

use Illuminate\Support\ServiceProvider;
use Monitaroo\Client;
use Monitaroo\Monitaroo;

class MonitarooServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/monitaroo.php', 'monitaroo');

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['monitaroo'];

            if (empty($config['api_key'])) {
                throw new \RuntimeException('Monitaroo API key is required. Set MONITAROO_API_KEY in your .env file.');
            }

            return Monitaroo::init([
                'apiKey' => $config['api_key'],
                'endpoint' => $config['endpoint'] ?? 'https://api.monitaroo.com',
                'service' => $config['service'] ?? $app['config']['app.name'] ?? 'laravel',
                'environment' => $config['environment'] ?? $app->environment(),
                'host' => $config['host'] ?? gethostname() ?: '',
                'batchSize' => $config['batch_size'] ?? 100,
                'autoFlush' => $config['auto_flush'] ?? true,
            ]);
        });

        // Alias for convenience
        $this->app->alias(Client::class, 'monitaroo');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/monitaroo.php' => config_path('monitaroo.php'),
            ], 'monitaroo-config');
        }

        // Auto-initialize if API key is configured
        if ($this->app['config']['monitaroo.api_key']) {
            $this->app->make(Client::class);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            Client::class,
            'monitaroo',
        ];
    }
}
