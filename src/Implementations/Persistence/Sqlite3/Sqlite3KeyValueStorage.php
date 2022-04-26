<?php

namespace App\Implementations\Persistence\Sqlite3;

use SQLite3;
use Exception;
use App\Settings;
use App\Contracts\Caching\KeyValueCache;
use App\Contracts\Persistence\KeyValueStorage;

class Sqlite3KeyValueStorage implements KeyValueStorage {
    private KeyValueCache $cache;
    private SQLite3 $db;
    
    public function __construct(KeyValueCache $cache) {
        $this->cache = $cache;
        $this->Initialize();
    }

    public function Initialize() {
        if (!isset($this->cache)) {
            throw new Exception('cache_required');
        }

        $this->db = new SQLite3(Settings::SQLITE3_DB_PATH);
        $this->db->exec("CREATE TABLE IF NOT EXISTS `key_values` (`key` INTEGER PRIMARY KEY AUTOINCREMENT, `value` VARCHAR(2048) NOT NULL UNIQUE);");
        $this->db->exec("INSERT OR IGNORE INTO `key_values` (`key`, `value`) VALUES (123456, 'SEED');");
        
        $items = array();
        $result = $this->db->query("SELECT * FROM `key_values`;");
        while ($row = $result->fetchArray()) {
            $items[$row['key']] = $row['value'];
        }

        $this->cache->Load($items);
    }
	
    public function Persist(string $value): int {
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `value` = :value;");
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $result = $stmt->execute();
        if ($row = $result->fetchArray()) {
            return $row['key'];
        }
        
        $stmt = $this->db->prepare("INSERT INTO `key_values` (`value`) VALUES (:value);");
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $stmt->execute();
        
        return $this->db->lastInsertRowID();
    }
	
    public function Retrieve(int $key, bool $bypassCache): string {
        if($bypassCache) {
            return $this->cache->Get($key);
        }
        
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `key` = :key;");
        $stmt->bindValue(':key', $key, SQLITE3_INTEGER);

        $result = $stmt->execute();
        if ($row = $result->fetchArray()) {
            return $row['value'];
        }

        throw new Exception('key_not_found');
    }

    public function Update(int $key, string $value) {
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `key` = :key;");
        $stmt->bindValue(':key', $key, SQLITE3_INTEGER);
        $result = $stmt->execute();
        if ($result->fetchArray()) {
            $stmt = $this->db->prepare("UPDATE `key_values` SET `value` = :value WHERE `key` = :key;");
            $stmt->bindValue(':value', $value, SQLITE3_TEXT);
            $stmt->bindValue(':key', $key, SQLITE3_INTEGER);
            $stmt->execute();
            return $value;
        }
        
        throw new Exception('key_not_found');
    }

    public function Remove(int $key) {
        $stmt = $this->db->prepare("DELETE FROM `key_values` WHERE `key` = :key;");
        $stmt->bindValue(':key', $key, SQLITE3_INTEGER);
        $stmt->execute();
    }
	
    public function Destroy() {
        unlink(Settings::SQLITE3_DB_PATH);
    }
}

?>
