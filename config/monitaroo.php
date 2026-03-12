<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Monitaroo API Key
    |--------------------------------------------------------------------------
    |
    | Your Monitaroo API key for authentication. Generate one from your
    | Monitaroo dashboard under Settings > Developer > API Keys.
    |
    */

    'api_key' => env('MONITAROO_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoint
    |--------------------------------------------------------------------------
    |
    | The Monitaroo API endpoint. You typically don't need to change this
    | unless you're using a custom or self-hosted instance.
    |
    */

    'endpoint' => env('MONITAROO_ENDPOINT', 'https://api.monitaroo.com'),

    /*
    |--------------------------------------------------------------------------
    | Service Name
    |--------------------------------------------------------------------------
    |
    | The name of your service/application. This will be attached to all
    | logs and metrics. Defaults to your app name.
    |
    */

    'service' => env('MONITAROO_SERVICE', env('APP_NAME', 'laravel')),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | The environment name (production, staging, local, etc.).
    | Defaults to your Laravel environment.
    |
    */

    'environment' => env('MONITAROO_ENVIRONMENT', env('APP_ENV', 'production')),

    /*
    |--------------------------------------------------------------------------
    | Host Name
    |--------------------------------------------------------------------------
    |
    | The hostname of this server. Useful for distinguishing logs from
    | different servers. Auto-detected if not set.
    |
    */

    'host' => env('MONITAROO_HOST'),

    /*
    |--------------------------------------------------------------------------
    | Batch Size
    |--------------------------------------------------------------------------
    |
    | Number of logs/metrics to buffer before automatically flushing.
    | Lower values = more frequent API calls, higher values = more memory.
    |
    */

    'batch_size' => env('MONITAROO_BATCH_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Auto Flush
    |--------------------------------------------------------------------------
    |
    | Automatically flush buffered logs/metrics at the end of each request.
    | Disable this if you want manual control over when data is sent.
    |
    */

    'auto_flush' => env('MONITAROO_AUTO_FLUSH', true),

];
