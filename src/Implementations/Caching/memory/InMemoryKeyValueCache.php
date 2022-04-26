<?php

namespace App\Implementations\Caching\Memory;

use Exception;
use App\Contracts\Caching\KeyValueCache;

class InMemoryKeyValueCache implements KeyValueCache {
    private array $collection;

    public function __construct() {
        $this->collection = array();
    }
    
    public function Load(array $content) {
        $this->collection = $content;
    }
    
    public function Put(int $key, string $value) {
        $this->collection[$key] = $value;
    }
    
    public function Get(int $key): string {
        if (!array_key_exists($key, $this->collection)) {
            throw new Exception('key_not_found');
        }

        return $this->collection[$key];
    }

    public function KeyExists(int $key): bool {
        return array_key_exists($key, $this->collection);
    }
}

?>
