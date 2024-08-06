<?php

namespace App\Utils;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    /**
     * @var Logger|null Singleton instance of Logger.
     */
    private static ?Logger $logger = null;

    /**
     * Get the singleton instance of Logger.
     *
     * @return Logger
     */
    public static function getLogger(): Logger
    {
        if (self::$logger === null) {
            // Create a logger instance
            self::$logger = new Logger('app');

            // Add a handler to log messages to a file
            $logPath = __DIR__ . '/../../logs/app.log';
            self::$logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
        }

        return self::$logger;
    }
}
