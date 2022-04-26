<?php

namespace App\Implementations\Network;

use Exception;
use App\Contracts\Network\HttpRouter;
use App\Contracts\Network\HttpRouterType;
use App\Implementations\Network\DefaultHttpRouter;

class NetworkFactory {
    public function CreateHttpRouter(HttpRouterType $componentType): HttpRouter {
        return match ($componentType) {
            HttpRouterType::DefaultHttpRouter => new DefaultHttpRouter(),
            default => throw new Exception('invalid_router_type')
        };
    }
}

?>
