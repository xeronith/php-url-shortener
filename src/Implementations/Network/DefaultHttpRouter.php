<?php

namespace App\Implementations\Network;

use Exception;
use App\Contracts\Network\HttpRouter;
use App\Contracts\Api\RESTApiController;
use App\Contracts\Modules\UrlShortener;
use App\Contracts\Logging\Logger;
use App\Contracts\Security\SecurityHandler;
use App\Implementations\Network\HttpUtil;

class DefaultHttpRouter implements HttpRouter {
    private Logger $logger;
    private SecurityHandler $securityHandler;
    private array $controllers = array();

    public function Bind(string $path, RESTApiController $controller) {
        $this->controllers[$path] = $controller;
    }

    public function Run(Logger $logger, SecurityHandler $securityHandler, UrlShortener $shortener) {
        if (!isset($logger)) {
            throw new Exception('logger_required');
        }

        if (!isset($securityHandler)) {
            throw new Exception('security_handler_required');
        }

        $this->logger = $logger;
        $this->securityHandler = $securityHandler;

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $path = $parsed_url['path'];

        header('content-type: application/json; charset=utf-8');

        try {
            if (preg_match('/^\/[a-zA-Z0-9]*$/', $path)) {
                if($_SERVER['REQUEST_METHOD'] != 'GET') {
                    HttpUtil::MethodNotAllowed();
                }

                $key = ltrim($path, '/');
                HttpUtil::Redirect($shortener->Expand($key));
            } else {
                if (!isset($_SERVER['HTTP_AUTHORIZATION']) || 
                    !str_starts_with($_SERVER['HTTP_AUTHORIZATION'], 'Bearer ') ||
                    !$this->securityHandler->VerifyToken(ltrim($_SERVER['HTTP_AUTHORIZATION'], 'Bearer '))) {
                        HttpUtil::Unauthorized();
                }

                $body = file_get_contents("php://input");
                if (!isset($body) || trim($body) == '') {
                    HttpUtil::BadRequest();
                }

                foreach($this->controllers as $controllerPath => $controller) {
                    if (str_starts_with($path, $controllerPath)) {
                        $body = json_decode($body);
                        switch($_SERVER['REQUEST_METHOD']) {
                            case 'GET':
                                HttpUtil::Success($controller->Get($body));
                            case 'POST':
                                HttpUtil::Success($controller->Post($body));
                            case 'PUT':
                                HttpUtil::Success($controller->Put($body));
                            case 'DELETE':
                                HttpUtil::Success($controller->Delete($body));
                            default:
                                HttpUtil::MethodNotAllowed();
                        }
                    }    
                }

                HttpUtil::NotFound();
            }
        } catch (Exception $ex) {
            HttpUtil::ServerError($ex->getMessage());
        }
    }
}

?>
