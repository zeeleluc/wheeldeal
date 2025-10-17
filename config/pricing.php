<?php

return [

    'seasonal' => [
        'high' => [
            'months' => [6, 7, 8, 12],
            'multiplier' => 1.25,
        ],
        'low' => [
            'months' => [1, 2, 11],
            'multiplier' => 0.9,
        ],
    ],

    'demand' => [
        'threshold' => 0.8,
        'multiplier' => 1.2,
    ],

    'availability' => [
        'threshold' => 0.2,
        'multiplier' => 1.15,
    ],
];
