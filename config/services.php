<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'binance' => [
        'key' => 'Uj6bK02llWbv1nOU9iLF7uQSynkWgfhw1y8iu0dND9JWGPdbi4F1pCpE4I2oiIyT',
        'secret' => 'NGvFTJHomp3agbR7s7x8QVxwkRKAogK8cx3FhvQLoKcQuFXcFbzB08GEeDE10Csy',
        
        //'key' => 'Gb4EoXEbpW4dUjGrOUPbdM7Pe7QJrmTQ8WAZ5joldY194rkmU1kiby8MnEyAWug8',
        //'secret' => 'MvhN3yp5Zlbxtlgvm5AoFIldLVd9mrfmuxXqYO2wzpTtCrLYwszX1lP7L4apGCdc',

        //'key' => 'D96SuTqyz9F6h1BDsS0EwZcG738WAUpjdq3ONhIePbeudQFSbDmJ9vOfRM9eYFqk',
        //'secret' => 'R3ZMa1SircMYxK5gwCwiW7vBUL6mmsH8nU8LPJsod0Okh0LX7496TtnKwJRCSRQt',
    ]

];
