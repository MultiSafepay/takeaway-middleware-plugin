<?php

declare(strict_types=1);

namespace TakeawayPlugin\Api;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TokenManager
{
    private const CACHE_KEY = 'api_token';
    private string $loginUrl;
    private string $refreshUrl;
    private string $username;
    private string $password;
    private string $token;
    private Carbon $expires;

    public function __construct()
    {
        $this->loginUrl = config('takeaway.backend_api.login_url');
        $this->refreshUrl = config('takeaway.backend_api.refresh_url');
        $this->username = config('takeaway.backend_api.username');
        $this->password = config('takeaway.backend_api.password');
    }

    public function getToken(): bool|string
    {
        $this->loadFromCache();

        if (empty($this->token)) {
            $this->login();
        } else {
            $now = Carbon::now()->subSeconds(30);

            if ($now > $this->expires) {
                $this->refreshToken();
            }
        }

        return $this->token;
    }

    private function loadFromCache(): void
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return;
        }

        $data = Cache::get(self::CACHE_KEY);
        $this->token = $data['token'];
        $this->expires = $data['expires'];
    }

    private function login(): void
    {
        $payload = ['username' => $this->username, 'password' => $this->password];
        $response = Http::post($this->loginUrl, $payload)->throw()->json();

        $this->set($response);
    }

    /**
     * @param array<string, array<string>>|array<string, string> $response
     */
    private function set(array &$response): void
    {
        $this->token = $response['token'];
        $this->expires = new Carbon($response['expires']);

        Cache::put(self::CACHE_KEY, ['token' => $this->token, 'expires' => $this->expires]);
    }

    private function refreshToken(): void
    {
        $response = Http::withToken($this->token)->post($this->refreshUrl)->throw()->json();

        $this->set($response);
    }
}
