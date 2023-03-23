<?php

declare(strict_types=1);

namespace TakeawayPlugin\Http\Controllers;

use TakeawayPlugin\Http\Requests\CancelOrder;
use TakeawayPlugin\Http\Requests\ConfirmOrder;
use TakeawayPlugin\Jobs\OrderAccept;
use TakeawayPlugin\Jobs\OrderCancel;
use Illuminate\Http\Request;
use TakeawayPlugin\Jobs\OrderConfirm;

class Order extends Controller
{
    public function accept(Request $request): void
    {
        $job = new OrderAccept();

        $job->dispatch($request->all());
    }

    public function confirm(ConfirmOrder $request): void
    {
        $job = new OrderConfirm();

        $job->dispatch($request->all());
    }

    public function cancel(CancelOrder $request): void
    {
        $job = new OrderCancel();

        $job->dispatch($request->all());
    }
}
