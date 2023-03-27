<?php

declare(strict_types=1);

namespace TakeawayPlugin\Takeaway;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use TakeawayModels\Enum\StatusCode;
use Exception;

class ApiRequest
{
    private TokenManager $tokenManager;

    public function __construct(string $restaurant)
    {
        $this->tokenManager = new TokenManager($restaurant);
    }

    public function confirmOrder(string $id, string $key, Carbon $deliveryTime): void
    {
        $data = [
            'id' => $id,
            'status' => StatusCode::confirmed->value,
            'key' => $key,
            'changedDeliveryTime' => $deliveryTime->toW3cString(),
        ];

        $this->setStatus($data);
    }

    public function cancelOrder(string $id, string $key, string $reason): void
    {
        $data = [
            'id' => $id,
            'status' => StatusCode::error->value,
            'key' => $key,
            'text' => $reason,
        ];

        $this->setStatus($data);
    }

    /**
     * @param array<string, string> $data
     * @return array<string, array<string>>|array<string, string>|array<string, string>
     */
    protected function setStatus(array $data): string|array
    {
        $response = Http::withToken($this->tokenManager->getToken())
            ->post($this->tokenManager->getStatusUrl(), $data);

        if ($response->successful()) {
            return $response->json();
        }

        $message = 'Error requesting takeaway, data: '.print_r($data, true);
        $message .= PHP_EOL.' Response: '.$response->json();

        throw new Exception($message);
    }
}
