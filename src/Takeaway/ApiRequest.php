<?php

declare(strict_types=1);

namespace TakeawayPlugin\Takeaway;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use TakeawayModels\Order;

class ApiRequest
{
    private string $url;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->url = config('takeaway.takeaway_api.url');
        $this->username = config('takeaway.takeaway_api.username');
        $this->password = config('takeaway.takeaway_api.password');
    }

    public function getOrders(string $restaurantId): Collection
    {
        $response = $this->send('GET', "orders/$restaurantId");

        return Order::collection($response);
    }

    public function confirmOrder(string $id, string $orderKey): void
    {
        $data = [
            'id' => $id,
            'key' => $orderKey,
            'status' => 'confirmed',
        ];

        $this->send('POST', 'status', $data);
    }

    public function changeDeliveryTime(string $id, string $orderKey, Carbon $date): void
    {
        $data = [
            'id' => $id,
            'key' => $orderKey,
            'status' => 'confirmed_change_delivery_time',
            'changedDeliveryTime' => $date->toW3cString(),
        ];

        $this->send('POST', 'status', $data);
    }

    public function reportError(string $id, string $orderKey, string $error): void
    {
        $data = [
            'id' => $id,
            'key' => $orderKey,
            'status' => 'error',
            'text' => $error,
        ];

        $this->send('POST', 'status', $data);
    }

    /**
     * @param string $method
     * @param string $path
     * @param null|array<string, array<string>>|array<string, string>|array<string, string> $data
     * @return array<string, array<string>>|array<string, string>|array<string, string>
     */
    protected function send(string $method, string $path, null|array $data = null): string|array
    {
        $response = Http::withBasicAuth($this->username, $this->password)->$method($this->url.$path, $data);

        if ($response->successful()) {
            return $response->json();
        }

        $message = "Error requesting takeaway, method: $method, path: $path, data: ".print_r($data, true);
        $message .= PHP_EOL.' Response: '.$response->json();

        throw new Exception($message);
    }
}
