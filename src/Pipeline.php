<?php

namespace App;

use App\Contracts\Logging\LoggerType;
use App\Contracts\Caching\KeyValueCacheType;
use App\Contracts\Modules\EncoderType;
use App\Contracts\Network\HttpRouterType;
use App\Contracts\Persistence\KeyValueStorageType;
use App\Contracts\Modules\UrlShortenerType;
use App\Contracts\Security\SecurityHandlerType;
use App\Implementations\Api\UrlController;
use App\Implementations\Logging\LoggingFactory;
use App\Implementations\Caching\CachingFactory;
use App\Implementations\Modules\EncoderFactory;
use App\Implementations\Network\NetworkFactory;
use App\Implementations\Persistence\PersistenceFactory;
use App\Implementations\Modules\UrlShortenerFactory;
use App\Implementations\Security\SecurityFactory;

class Pipeline {
    public function Start() {
        $router = (new NetworkFactory())->CreateHttpRouter(HttpRouterType::DefaultHttpRouter);
        $logger = (new LoggingFactory())->CreateLogger(LoggerType::DefaultLogger);
        $cache = (new CachingFactory())->CreateKeyValueCache(KeyValueCacheType::InMemoryKeyValueCache);
        $storage = (new PersistenceFactory())->CreateKeyValueStorage(KeyValueStorageType::Sqlite3KeyValueStorage, $cache);
        $encoder = (new EncoderFactory())->CreateEncoder(EncoderType::BijectiveEncoder);
        $shortener = (new UrlShortenerFactory())->CreateUrlShortener(UrlShortenerType::DefaultUrlShortener, $storage, $encoder);
        $securityHandler = (new SecurityFactory())->CreateSecurityHandler(SecurityHandlerType::BasicSecurityHandler);

        $router->Bind('/api/url', new UrlController($shortener));
        
        $router->Run($logger, $securityHandler, $shortener);
    }
}

?>
