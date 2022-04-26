<?php 

namespace App\Contracts\Persistence;

enum KeyValueStorageType {
    case VolatileKeyValueStorage;
    case Sqlite3KeyValueStorage;
    case MySQLKeyValueStorage;
}

?>
