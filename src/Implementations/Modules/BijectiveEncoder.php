<?php

namespace App\Implementations\Modules;

use App\Contracts\Modules\Encoder;

class BijectiveEncoder implements Encoder {
    public string $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    public int $base;

    public function __construct() {
        $this->base = strlen($this->characters);
    }
    
    public function Encode(int $i) : string {
        if ($i == 0) return $this->characters[0];
        
        $s = '';
        while ($i > 0) {  
            $s .= $this->characters[$i % $this->base];
            $i = intdiv($i, $this->base);
        }
        
        return join('', str_split(strrev($s)));
    }

    public function Decode(string $s): int  {
        $i = 0;
        
        $array = str_split($s);
        foreach ($array as $c) {
            $i = ($i * $this->base) + strpos($this->characters, $c);
        }
        
        return $i;
    }
}
