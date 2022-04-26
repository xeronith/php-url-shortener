<?php

namespace App\Implementations\Security;

use App\Contracts\Security\SecurityHandler;

class BasicSecurityHandler implements SecurityHandler {
    public function VerifyToken(string $token): bool {
        switch($token) {
            case "VALID_API_KEY_1":
            case "VALID_API_KEY_2":
            case "VALID_API_KEY_3":
                return true;
            default:
                return false;
        }
    }
}

?>
