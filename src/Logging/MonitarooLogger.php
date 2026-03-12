<?php

declare(strict_types=1);

namespace Monitaroo\Laravel\Logging;

use Monolog\Logger;
use Monitaroo\Client;

/**
 * Custom log channel factory for Laravel's logging system.
 *
 * Usage in config/logging.php:
 *
 * 'channels' => [
 *     'monitaroo' => [
 *         'driver' => 'custom',
 *         'via' => \Monitaroo\Laravel\Logging\MonitarooLogger::class,
 *         'level' => 'debug',
 *     ],
 * ],
 */
class MonitarooLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $client = app(Client::class);
        $level = isset($config['level']) ? $config['level'] : 'debug';

        $handler = new MonitarooHandler($client, $level);

        return new Logger('monitaroo', [$handler]);
    }
}
