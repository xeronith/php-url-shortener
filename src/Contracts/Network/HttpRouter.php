<?php 

namespace App\Contracts\Network;

use App\Contracts\Api\RESTApiController;
use App\Contracts\Logging\Logger;
use App\Contracts\Modules\UrlShortener;
use App\Contracts\Security\SecurityHandler;

interface HttpRouter {
    public function Bind(string $path, RESTApiController $controller);
    public function Run(Logger $logger, SecurityHandler $securityHandler, UrlShortener $shortener);
}

?>
