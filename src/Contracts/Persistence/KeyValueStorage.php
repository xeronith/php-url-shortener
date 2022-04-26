<?php 

namespace App\Contracts\Persistence;

interface KeyValueStorage {
    public function Initialize();
	public function Persist(string $value): int;
	public function Retrieve(int $key, bool $bypassCache): string;
    public function Update(int $key, string $value);
    public function Remove(int $key);
	public function Destroy();
}

?>
