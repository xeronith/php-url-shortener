<?php

namespace App\Implementations\Persistence\MySQL;

use PDO;
use Exception;
use App\Settings;
use App\Contracts\Caching\KeyValueCache;
use App\Contracts\Persistence\KeyValueStorage;

class MySQLKeyValueStorage implements KeyValueStorage {
    private KeyValueCache $cache;
    private PDO $db;
    
    public function __construct(KeyValueCache $cache) {
        $this->cache = $cache;
        $this->Initialize();
    }

    public function Initialize() {
        if (!isset($this->cache)) {
            throw new Exception('cache_required');
        }

        $this->db = new PDO(sprintf("mysql:host=%s;dbname=%s", Settings::MYSQL_SERVER, Settings::MYSQL_DATABASE), Settings::MYSQL_USER, Settings::MYSQL_PASSWORD);
        $this->db->exec("CREATE TABLE IF NOT EXISTS `key_values` (`key` INTEGER PRIMARY KEY AUTO_INCREMENT, `value` VARCHAR(2048) NOT NULL UNIQUE) AUTO_INCREMENT = 123456;");
        
        $items = array();
        $result = $this->db->query("SELECT * FROM `key_values`;");
        while ($row = $result->fetch()) {
            $items[$row['key']] = $row['value'];
        }

        $this->cache->Load($items);
    }
	
    public function Persist(string $value): int {
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `value` = :value;");
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            return $row['key'];
        }
        
        $stmt = $this->db->prepare("INSERT INTO `key_values` (`value`) VALUES (:value);");
        $stmt->bindValue(':value', $value, SQLITE3_TEXT);
        $stmt->execute();
        
        return $this->db->lastInsertId();
    }
	
    public function Retrieve(int $key, bool $bypassCache): string {
        if($bypassCache) {
            return $this->cache->Get($key);
        }
        
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `key` = :key;");
        $stmt->bindValue(':key', $key, SQLITE3_INTEGER);

        $stmt->execute();
        if ($row = $stmt->fetch()) {
            return $row['value'];
        }

        throw new Exception('key_not_found');
    }

    public function Update(int $key, string $value) {
        $stmt = $this->db->prepare("SELECT * FROM `key_values` WHERE `key` = :key;");
        $stmt->bindValue(':key', $key, SQLITE3_INTEGER);
        $stmt->execute();
        if ($stmt->fetch()) {
            $stmt = $this->db->prepare("UPDATE `key_values` SET (`value` = :value) WHERE `key` = :key;");
            $stmt->bindValue(':value', $value, SQLITE3_TEXT);
            $stmt->bindValue(':key', $key, SQLITE3_INTEGER);
            $stmt->execute();
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
