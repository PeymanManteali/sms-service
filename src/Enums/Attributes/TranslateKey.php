<?php

namespace SmsService\Enums\Attributes;

use Attribute;

#[Attribute]
class TranslateKey
{
    public function __construct(
        public string $translateKey,
    ) {
    }
}
