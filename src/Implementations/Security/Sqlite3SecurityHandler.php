<?php

namespace App\Implementations\Security;

use App\Contracts\Security\SecurityHandler;

class Sqlite3SecurityHandler implements SecurityHandler {
    public function VerifyToken(string $token): bool {
        //TODO: Implement this
        return false;
    }
}

?>
