<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
	protected function redirectTo(Request $request): ?string
	{
		return $request->expectsJson() ? null : route('signin');
	}
}
