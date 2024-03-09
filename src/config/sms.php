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

        'kavenegar' => ['driver' => \SMSService\Drivers\KavenegarProvider::class,
            'parameters' => [
                'api-key',
                'template-id',
                'sender' //برای تایپ text لازم است.
            ]
        ],

        'sms0098' => ['driver' => \SMSService\Drivers\Sms0098Provider::class,
            'parameters' => ['line-number', 'password', 'username']
        ],

        'sms_ir' => ['driver' => \SMSService\Drivers\SmsIrProvider::class,
            'parameters' => ['api-key', 'line-number', 'template-id']
        ],
    ],
];

