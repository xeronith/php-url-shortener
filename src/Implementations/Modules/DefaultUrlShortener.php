<?php

namespace App\Implementations\Modules;

use Exception;
use App\Contracts\Modules\Encoder;
use App\Contracts\Modules\UrlShortener;
use App\Contracts\Persistence\KeyValueStorage;
use App\Settings;

class DefaultUrlShortener implements UrlShortener {
    private KeyValueStorage $storage;
    private Encoder $encoder;

    public function __construct(KeyValueStorage $storage, Encoder $encoder) {
        if (!$this->IsValid(Settings::BASE_URL)) {
            throw new Exception('invalid_base_url');
        }

        $this->storage = $storage;
        $this->encoder = $encoder;
    }
	
    public function IsValid(string $url): bool {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
	
    public function Shorten(string $url): string {
        if (!$this->IsValid($url)) {
            throw new Exception('invalid_url');
        }

        $url = filter_var($url, FILTER_SANITIZE_URL);
        $key = $this->storage->Persist($url);

        $encodedKey = $this->encoder->Encode($key);
        return sprintf('%s%s', Settings::BASE_URL, $encodedKey);
    }
	
    public function Expand(string $key): string {
        $decodedKey = $this->encoder->Decode($key);
        return $this->storage->Retrieve($decodedKey, false);
    }

    public function Update(string $key, string $url) {
        $decodedKey = $this->encoder->Decode($key);
        $this->storage->Update($decodedKey, $url);
    }
	
    public function Remove(string $key) {
        $decodedKey = $this->encoder->Decode($key);
        $this->storage->Remove($decodedKey);
    }
}
