<?php

declare(strict_types=1);

namespace Monitaroo\Laravel\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monitaroo\Client;

/**
 * Monolog handler that sends logs to Monitaroo.
 * 
 * Compatible with both Monolog 2.x (Laravel 8/9) and Monolog 3.x (Laravel 10+).
 */
class MonitarooHandler extends AbstractProcessingHandler
{
    /** @var Client */
    private $client;

    /**
     * @param Client $client
     * @param int|string $level
     * @param bool $bubble
     */
    public function __construct(Client $client, $level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->client = $client;
    }

    /**
     * @inheritDoc
     * @param array $record
     * @return void
     */
    protected function write(array $record): void
    {
        $level = $this->mapLevel($record['level']);
        $message = $record['message'];
        $context = isset($record['context']) ? $record['context'] : [];

        // Add extra data to context
        if (!empty($record['extra'])) {
            $context['extra'] = $record['extra'];
        }

        $this->client->log($level, $message, $context);
    }

    /**
     * Map Monolog level to Monitaroo level.
     *
     * @param int $level
     * @return string
     */
    private function mapLevel($level)
    {
        switch ($level) {
            case Logger::DEBUG:
                return 'debug';
            case Logger::INFO:
                return 'info';
            case Logger::NOTICE:
                return 'info';
            case Logger::WARNING:
                return 'warn';
            case Logger::ERROR:
                return 'error';
            case Logger::CRITICAL:
            case Logger::ALERT:
            case Logger::EMERGENCY:
                return 'fatal';
            default:
                return 'info';
        }
    }
}
