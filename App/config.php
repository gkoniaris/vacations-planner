<?php

global $settings;

$settings = [
    'FRONTEND_URL' => getenv('FRONTEND_URL'),
    'BACKEND_URL' => getenv('BACKEND_URL'),
    'DATABASE' => [
        'DB_HOST' => getenv('DB_HOST'),
        'DB_USERNAME' => getenv('DB_USERNAME'),
        'DB_PASSWORD' => getenv('DB_PASSWORD'),
        'DB_NAME' => getenv('DB_NAME')
    ],
    'MAIL' => [
        'HOST' => getenv('MAIL_HOST'),
        'PORT' => getenv('MAIL_PORT'),
        'USERNAME' => getenv('MAIL_USERNAME'),
        'PASSWORD' => getenv('MAIL_PASSWORD')
    ]
];
