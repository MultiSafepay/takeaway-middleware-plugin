<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use TakeawayPlugin\Http\Controllers\Order;

Route::prefix('takeaway')->group(
    function () {
        Route::post('orders/accept', [Order::class, 'accept']);
        Route::post('orders/confirm', [Order::class, 'confirm']);
        Route::post('orders/cancel', [Order::class, 'cancel']);
        Route::post('orders/cancelled', [Order::class, 'cancelled']);
    }
);
