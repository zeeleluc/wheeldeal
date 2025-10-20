<?php

return [
    'apk' => [
        'min_days' => 0,
        'max_years' => 2,
    ],

    'rental' => [
        'min_days' => 1,
        'max_days' => 30,
        'max_days_shift' => 14,
        'frequency_days' => 7,
    ],

    'expiration' => [
        'draft_minutes' => 10,
        'pending_payment_minutes' => 30,
    ],
];
