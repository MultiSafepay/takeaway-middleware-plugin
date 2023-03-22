<?php

declare(strict_types=1);

namespace TakeawayPlugin;

use Illuminate\Support\ServiceProvider;

class TakeawayPluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php',
            'takeaway'
        );
    }

    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__.'/config/config.php' => config_path('config.php'),
            ],
        );

        $this->loadRoutesFrom(__DIR__.'/routes/takeaway.php');
    }
}
