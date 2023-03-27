<?php

declare(strict_types=1);

namespace TakeawayPlugin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TakeawayPlugin\Takeaway\ApiRequest;
use TakeawayPlugin\Api\ApiRequest as BackendApi;

class OrderCancel implements ShouldQueue
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

        $this->cancel();
        $this->confirmCancel();
    }

    private function cancel(): void
    {
        $api = new ApiRequest($this->data['restaurant']);

        $api->cancelOrder($this->data['id'], $this->data['key'], $this->data['reason']);
    }

    private function confirmCancel(): void
    {
        $api = new BackendApi();

        $api->confirm('order-cancel', $this->data);
    }
}
