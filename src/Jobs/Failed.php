<?php

declare(strict_types=1);

namespace TakeawayPlugin\Jobs;

use NotificationFailure\Model as NotificationFailureModel;
use NotificationFailure\Notification as NotificationFailure;
use NotificationFailure\LogLevel;
use Throwable;

trait Failed
{
    public function failed(Throwable $exception): void
    {
        $data = new NotificationFailureModel(
            [
                'logLevel' => LogLevel::emergency->value,
                'problem' => $exception->getMessage(),
                /** @phpstan-ignore-next-line */
                'data' => $this->data,
            ]
        );

        $user = null;

        if (class_exists('Tests\User')) {
            /** @phpstan-ignore-next-line */
            $user = \Tests\User::find(1);
        } else {
            /** @phpstan-ignore-next-line */
            $user = \App\Models\User::where('role', 'support')->first();
        }

        $user->notify(new NotificationFailure($data));
    }
}
