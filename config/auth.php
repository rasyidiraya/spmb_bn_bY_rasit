<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'pengguna' => [
            'driver' => 'session',
            'provider' => 'pengguna',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
        'verifikator' => [
            'driver' => 'session',
            'provider' => 'verifikator',
        ],
        'keuangan' => [
            'driver' => 'session',
            'provider' => 'keuangan',
        ],
        'kepsek' => [
            'driver' => 'session',
            'provider' => 'kepsek',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'pengguna' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pendaftar\Pengguna::class,
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pendaftar\Pengguna::class,
        ],
        'verifikator' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pendaftar\Pengguna::class,
        ],
        'keuangan' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pendaftar\Pengguna::class,
        ],
        'kepsek' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pendaftar\Pengguna::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];