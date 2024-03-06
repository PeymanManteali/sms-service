<?php
return [
    'deactivation' => [
        'first_count' => 2,
        'first_time' => 5,
        'second_count' => 4,
        'second_time' => 10,
        'third_count' => 6,
        'third_time' => 30,
        'fourth_count' => 8,
        'fourth_time' => 60,
        'fifth_count' => 10,
        'fifth_time' => 120
    ],

    'providers' => [

        'kavenegar' => ['driver' => \SMSService\SmsProviders\KavenegarProvider::class,
            'parameters' => ['api-key', 'template-id']
        ],

        '0098' => ['driver' => \SMSService\SmsProviders\Sms0098Provider::class,
            'parameters' => []
        ],

        'sms_ir' => ['driver' => \SMSService\SmsProviders\SmsIrProvider::class,
            'parameters' => []
        ],
    ],
];

