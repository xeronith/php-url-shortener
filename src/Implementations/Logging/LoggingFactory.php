<?php

namespace App\Implementations\Logging;

use Exception;
use App\Contracts\Logging\Logger;
use App\Contracts\Logging\LoggerType;
use App\Implementations\Logging\DefaultLogger;

class LoggingFactory {
    public function CreateLogger(LoggerType $componentType): Logger {
        return match ($componentType) {
            LoggerType::DefaultLogger => new DefaultLogger(),
            default => throw new Exception('invalid_logger_type')
        };
    }
}

?>
