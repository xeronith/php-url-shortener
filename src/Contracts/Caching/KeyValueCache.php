<?php

namespace App\Contracts\Caching;

interface KeyValueCache {
    public function Load(array $content);
    public function Put(int $key, string $value);
    public function Get(int $key): string;
    public function KeyExists(int $key): bool;
}

?>
