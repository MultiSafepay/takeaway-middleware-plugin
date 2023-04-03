<?php

declare(strict_types=1);

namespace TakeawayPlugin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use TakeawayPlugin\Takeaway\ApiRequest;
use TakeawayPlugin\Api\ApiRequest as BackendApi;

class OrderConfirm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Failed;

    /**
     *
     * @var array<string, array<string>>|array<string, string> $data
     */
    private array $data;

    /**
     * Execute the job.
     *
     * @param array<string, array<string>>|array<string, string> $request
     *
     * @return void
     */
    public function handle(array $request): void
    {
        $this->data = $request;

        $this->acceptOrder();
        $this->confirmAccepted();
    }

    private function acceptOrder(): void
    {
        $api = new ApiRequest($this->data['restaurant']);

        $api->confirmOrder($this->data['id'], $this->data['key'], new Carbon($this->data['deliveryTime']));
    }

    private function confirmAccepted(): void
    {
        $api = new BackendApi();

        $api->confirm('order-accept', $this->data);
    }
}
