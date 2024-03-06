<?php

namespace SmsService\Enums;

use SmsService\Enums\Attributes\TranslateKey;
use SmsService\Enums\Concerns\GetsAttributes;
use SmsService\Enums\Interfaces\GetValueInterface;

enum SmsStatus: string implements GetValueInterface
{
    use GetsAttributes;

    #[TranslateKey('sms.status.scheduled')]
    case Scheduled = 'SCHEDULED';
    #[TranslateKey('sms.status.sent')]
    case Sent = 'SENT';
    #[TranslateKey('sms.status.delivered')]
    case Delivered = 'DELIVERED';
    #[TranslateKey('sms.status.delivery_failed')]
    case DeliveryFailed = 'DELIVERY_FAILED';
    #[TranslateKey('sms.status.invalid_status')]
    case InvalidStatus = 'INVALID_STATUS';
    #[TranslateKey('sms.status.failed')]
    case Failed = 'FAILED';
}
