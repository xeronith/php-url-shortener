<?php

namespace App\Implementations\Logging;

use App\Contracts\Logging\Logger;

class DefaultLogger implements Logger {
    public function Info(...$args) {
        echo($args);
    }
    
    public function Fail(...$args) {
        error_log($args);
    }
}

?>
