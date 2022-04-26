<?php

namespace App\Implementations\Security;

use Exception;
use App\Contracts\Security\SecurityHandler;
use App\Contracts\Security\SecurityHandlerType;

class SecurityFactory {
    public function CreateSecurityHandler(SecurityHandlerType $componentType): SecurityHandler {
        return match ($componentType) {
            SecurityHandlerType::BasicSecurityHandler => new BasicSecurityHandler(),
            SecurityHandlerType::OAuthSecurityHandler => new OAuthSecurityHandler(),
            SecurityHandlerType::Sqlite3SecurityHandler => new Sqlite3SecurityHandler(),
            default => throw new Exception('invalid_security_handler_type')
        };
    }
}

?>
