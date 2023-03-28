<?php

declare(strict_types=1);

namespace TakeawayPlugin\Takeaway;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TokenManager
{
    private const CACHE_KEY = 'takeaway_token';
    private const LOGIN_URL = 'https://posapi.takeaway.com/login';
    private string $restaurant;
    private string $token;
    private string $statusUrl;

    public function __construct(string $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function getToken(): string
    {
        $this->load();

        return $this->token;
    }

    public function getStatusUrl(): string
    {
        $this->load();

        return $this->statusUrl;
    }

    private function load(): void
    {
        $this->loadFromCache();

        if (empty($this->token)) {
            $this->login();
        }
    }

    private function loadFromCache(): void
    {
        if (! Cache::has(self::CACHE_KEY.$this->restaurant)) {
            return;
        }

        $data = Cache::get(self::CACHE_KEY.$this->restaurant);

        $this->set($data);
    }

    /**
     * @param array<string, array<string>>|array<string, string> $data
     */
    private function set(array &$data): void
    {
        $this->token = $data['token'];
        $this->statusUrl = $data['statusUrl'];
    }

    private function login(): void
    {
        $middlewareUrl = config('takeaway.middlewareUrl');

        $payload = [
            'apiKey' => config('takeaway.takeaway_api.apiKey'),
            'restaurant' => $this->restaurant,
            'orderUrl' => "$middlewareUrl/takeaway/orders/accept",
            'driverUpdateUrl' => 'https://nodriverupdates',
            'aliveUrl' => "$middlewareUrl/takeaway/alive",
            'version' => config('takeaway.takeaway_api.version'),
            'clientKey' => config('takeaway.takeaway_api.clientKey'),
            'orderCancelled' => "$middlewareUrl/takeaway/orders/cancelled",
        ];

        $response = Http::post(self::LOGIN_URL, $payload)->throw()->json();

        $this->set($response);

        Cache::put(self::CACHE_KEY.$this->restaurant, $response);
    }
}
