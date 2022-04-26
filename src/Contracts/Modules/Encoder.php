<?php 

namespace App\Contracts\Modules;

interface Encoder {
    public function Encode(int $arg) : string;
	public function Decode(string $arg): int;
}

?>
