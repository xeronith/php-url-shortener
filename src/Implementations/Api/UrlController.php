<?php

namespace App\Implementations\Api;

use App\Contracts\Api\RESTApiController;
use App\Contracts\Modules\UrlShortener;
use App\Implementations\Network\HttpUtil;

class UrlController implements RESTApiController {
    private UrlShortener $shortener;
    
    public function __construct(UrlShortener $shortener) {
        $this->shortener = $shortener;
    }

    public function Get(mixed $body): string {
        HttpUtil::NotFound();
    }
    
    public function Post(mixed $body): string {
        return $this->shortener->Shorten($body->url);
    }
    
    public function Put(mixed $body): string {
        $this->shortener->Update($body->key, $body->url);
        return 'updated';
    }
    
    public function Delete(mixed $body): string {
        $this->shortener->Remove($body->key);
        return 'removed';
    }
}

?>
