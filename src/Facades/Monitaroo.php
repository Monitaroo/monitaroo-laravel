<?php

declare(strict_types=1);

namespace Monitaroo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Monitaroo\Client;

/**
 * @method static void trace(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void warn(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void fatal(string $message, array $context = [])
 * @method static void log(string $level, string $message, array $context = [])
 * @method static void increment(string $name, int $value = 1, array $tags = [])
 * @method static void gauge(string $name, float $value, array $tags = [])
 * @method static void timing(string $name, float $milliseconds, array $tags = [])
 * @method static void histogram(string $name, float $value, array $tags = [])
 * @method static callable startTimer(string $name, array $tags = [])
 * @method static void flush()
 * @method static \Monitaroo\Logger getLogger()
 *
 * @see \Monitaroo\Client
 */
class Monitaroo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
