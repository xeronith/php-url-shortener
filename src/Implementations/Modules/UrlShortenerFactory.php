<?php

namespace App\Implementations\Modules;

use Exception;
use App\Contracts\Modules\Encoder;
use App\Contracts\Modules\UrlShortener;
use App\Contracts\Modules\UrlShortenerType;
use App\Contracts\Persistence\KeyValueStorage;
use App\Implementations\Modules\DefaultUrlShortener;

class UrlShortenerFactory {
    public function CreateUrlShortener(UrlShortenerType $componentType, KeyValueStorage $storage, Encoder $encoder): UrlShortener {
        return match ($componentType) {
            UrlShortenerType::DefaultUrlShortener => new DefaultUrlShortener($storage, $encoder),
            default => throw new Exception('invalid_shortener_type')
        };
    }
}

?>
