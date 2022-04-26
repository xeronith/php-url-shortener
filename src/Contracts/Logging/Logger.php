<?php 

namespace App\Contracts\Logging;

interface Logger {
    public function Info(...$args);
    public function Fail(...$args);
}

?>
