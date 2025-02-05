<?php

return [
    'passport' => [
        'client_id' => env('PASSWORD_CLIENT_ID'),
        'client_secret' => env('PASSWORD_CLIENT_SECRET'),
    ],
    'default_pwd' => env('DEFAULT_PWD'),
    'god_user' => explode(',', env('GOD_USER')),
];
