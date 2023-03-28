<?php

declare(strict_types=1);

use TakeawayPlugin\Api\TokenManager;
use TakeawayPlugin\Api\ApiRequest;

return [
    'backend_api' => [
        'token_manager_class' => TokenManager::class,
        'api_handler_class' => ApiRequest::class,
        'url' => env('BACKEND_URL'),
        'login_url' => env('BACKEND_LOGIN_URL'),
        'refresh_url' => env('BACKEND_REFRESH_URL'),
        'username' => env('BACKEND_USERNAME'),
        'password' => env('BACKEND_PASSWORD'),
    ],
    'takeaway_api' => [
        'apiKey' => env('TAKEAWAY_APIKEY'),
        'middlewareUrl' => env('TAKEAWAY_MIDDLEWARE_URL'),
        'version' => env('TAKEAWAY_ALIVE_URL'),
        'clientKey' => env('TAKEAWAY_CLIENT_KEY'),
    ],
];
