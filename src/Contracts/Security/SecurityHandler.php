<?php 

namespace App\Contracts\Security;

interface SecurityHandler {
    public function VerifyToken(string $token): bool;
}

?>
