<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn(Request $request) {
		if ($request->isMethod('post')) {
			$user = User::where('email', $request->email)->first();

			if ($user && Hash::check($request->password, $user->password)) {
				Auth::login($user, $request->has('remember_me'));

				return redirect()->intended('/');
			}

			return AppHelper::redirect('signin', AppHelper::ERROR, ['Las credenciales introducidas no son correctas.']);
		}

		return view('auth.signin');
	}

	public function logout(Request $request)
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}
