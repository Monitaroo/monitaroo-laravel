<?php

declare(strict_types=1);

namespace Monitaroo\Laravel\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Monitaroo\Client;

/**
 * Monolog handler that sends logs to Monitaroo.
 */
class MonitarooHandler extends AbstractProcessingHandler
{
    private Client $client;

    public function __construct(Client $client, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void
    {
        $level = $this->mapLevel($record->level);
        $context = $record->context;

        // Add extra data to context
        if (!empty($record->extra)) {
            $context['extra'] = $record->extra;
        }

        $this->client->log($level, $record->message, $context);
    }

    /**
     * Map Monolog level to Monitaroo level.
     */
    private function mapLevel(Level $level): string
    {
        return match ($level) {
            Level::Debug => 'debug',
            Level::Info => 'info',
            Level::Notice => 'info',
            Level::Warning => 'warn',
            Level::Error => 'error',
            Level::Critical, Level::Alert, Level::Emergency => 'fatal',
        };
    }
}
