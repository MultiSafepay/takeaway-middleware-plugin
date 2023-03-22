<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseUser
{
    use Notifiable, HasFactory;

	protected static function newFactory(): Factory
	{
		return UserFactory::new();
	}
}
