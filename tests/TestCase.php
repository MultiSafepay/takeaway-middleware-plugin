<?php

declare(strict_types=1);

namespace Tests;

use TakeawayPlugin\TakeawayPluginServiceProvider;
use Dotenv\Dotenv;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        $env = Dotenv::createImmutable('/workspace');
        $env->load();

		return [
            TakeawayPluginServiceProvider::class,
		];
    }

    protected function defineDatabaseMigrations()
	{
		$this->loadLaravelMigrations();
	}
}
