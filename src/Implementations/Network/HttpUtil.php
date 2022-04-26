<?php

namespace App\Implementations\Network;

class HttpUtil {
    public static function Redirect($url) {
        header(sprintf('Location: %s', $url), true, 301);
        exit();
    }

    public static  function Success($html) {
        http_response_code(200);
        echo($html);
        exit();
    }

    public static  function NotFound() {
        http_response_code(404);
        echo('not_found');
        exit();
    }

    public static  function Unauthorized() {
        http_response_code(401);
        echo('unauthorized');
        exit();
    }

    public static  function Forbidden() {
        http_response_code(403);
        echo('forbidden');
        exit();
    }

    public static  function BadRequest() {
        http_response_code(400);
        echo('bad_request');
        exit();
    }

    public static  function ServerError($error) {
        http_response_code(500);
        echo($error);
        exit();
    }

    public static  function MethodNotAllowed() {
        http_response_code(405);
        echo('method_not_allowed');
        exit();
    }
}

?>
