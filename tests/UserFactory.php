<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\Factories\UserFactory as BaseFactory;

class UserFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;
}
