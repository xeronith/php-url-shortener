<?php

namespace App\Implementations\Modules;

use Exception;
use App\Contracts\Modules\Encoder;
use App\Contracts\Modules\EncoderType;

class EncoderFactory {
    public function CreateEncoder(EncoderType $componentType): Encoder {
        return match ($componentType) {
            EncoderType::BijectiveEncoder => new BijectiveEncoder(),
            default => throw new Exception('invalid_encoder_type')
        };
    }
}

?>
