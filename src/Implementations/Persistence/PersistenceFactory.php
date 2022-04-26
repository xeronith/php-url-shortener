<?php

namespace App\Implementations\Persistence;

use Exception;
use App\Contracts\Caching\KeyValueCache;
use App\Contracts\Persistence\KeyValueStorage;
use App\Contracts\Persistence\KeyValueStorageType;
use App\Implementations\Persistence\Sqlite3\Sqlite3KeyValueStorage;
use App\Implementations\Persistence\MySQL\MySQLKeyValueStorage;

class PersistenceFactory {
    public function CreateKeyValueStorage(KeyValueStorageType $componentType, KeyValueCache $cache): KeyValueStorage {
        return match ($componentType) {
            KeyValueStorageType::Sqlite3KeyValueStorage => new Sqlite3KeyValueStorage($cache),
            KeyValueStorageType::MySQLKeyValueStorage => new MySQLKeyValueStorage($cache),
            default => throw new Exception('invalid_storage_type')
        };
    }
}

?>
