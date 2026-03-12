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
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/monitaroo.php', 'monitaroo');

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['monitaroo'];

            if (empty($config['api_key'])) {
                throw new \RuntimeException('Monitaroo API key is required. Set MONITAROO_API_KEY in your .env file.');
            }

            $appName = $app['config']['app.name'];
            $appEnv = $app->environment();
            $hostname = gethostname();

            return Monitaroo::init([
                'apiKey' => $config['api_key'],
                'endpoint' => isset($config['endpoint']) ? $config['endpoint'] : 'https://api.monitaroo.com',
                'service' => isset($config['service']) ? $config['service'] : ($appName ?: 'laravel'),
                'environment' => isset($config['environment']) ? $config['environment'] : $appEnv,
                'host' => isset($config['host']) ? $config['host'] : ($hostname ?: ''),
                'batchSize' => isset($config['batch_size']) ? $config['batch_size'] : 100,
                'autoFlush' => isset($config['auto_flush']) ? $config['auto_flush'] : true,
            ]);
        });

        // Alias for convenience
        $this->app->alias(Client::class, 'monitaroo');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
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
     * @return array
     */
    public function provides()
    {
        return [
            Client::class,
            'monitaroo',
        ];
    }
}
