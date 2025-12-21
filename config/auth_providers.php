<?php
return [
    'providers' => [
        'google' => [
            'can_signup' => true,
        ],
        'facebook' => [
            'can_signup' => false,
        ],
    ],
    // whitelist to prevent arbitrary provider use
    'allowed' => ['google', 'facebook'],
];