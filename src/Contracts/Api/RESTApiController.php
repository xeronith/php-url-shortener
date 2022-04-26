<?php

namespace App\Contracts\Api;

interface RESTApiController {
    public function Get(mixed $body): string;
    public function Post(mixed $body): string;
    public function Put(mixed $body): string;
    public function Delete(mixed $body): string;
}

?>
