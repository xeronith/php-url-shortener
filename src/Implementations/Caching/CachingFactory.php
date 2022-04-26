<?php

namespace App\Implementations\Caching;

use Exception;
use App\Contracts\Caching\KeyValueCache;
use App\Contracts\Caching\KeyValueCacheType;
use App\Implementations\Caching\Memory\InMemoryKeyValueCache;

class CachingFactory {
    public function CreateKeyValueCache(KeyValueCacheType $componentType): KeyValueCache {
        return match ($componentType) {
            KeyValueCacheType::InMemoryKeyValueCache => new InMemoryKeyValueCache(),
            default => throw new Exception('invalid_cache_type')
        };
    }
}

?>
