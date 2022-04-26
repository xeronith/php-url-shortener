<?php 

namespace App\Contracts\Security;

enum SecurityHandlerType {
    case BasicSecurityHandler;
    case OAuthSecurityHandler;
    case Sqlite3SecurityHandler;
}

?>
