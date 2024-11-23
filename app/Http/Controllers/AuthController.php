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

	public function profile(Request $request) {
		if ($request->isMethod('put')) {
			$errors = AppHelper::validate(
				[
					'name' => trim($request->input('txtName')),
					'email' => trim($request->input('txtEmail')),
					'password' => trim($request->input('txtPassword')),
					'password_confirmation' => trim($request->input('txtPasswordConfirmation'))
				],
				[
					'name' => ['required', 'string', 'max:255'],
					'email' => ['required', 'string', 'email', 'max:255'],
					'password' => ['nullable', 'string', 'min:8', 'confirmed']
				]
			);

			if (count($errors) > 0) {
				return AppHelper::redirect('profile', AppHelper::ERROR, $errors);
			}

			$user = User::find(Auth::id());

			$user->name = $request->input('txtName');
			$user->email = $request->input('txtEmail');

			if ($request->input('txtPassword')) {
				$user->password = bcrypt(trim($request->input('txtPassword')));
			}

			$user->save();

			return AppHelper::redirect('profile', AppHelper::SUCCESS, ['Perfil actualizado correctamente.']);
		}

		return view('auth.profile');
	}

	public function logout(Request $request)
	{
		Auth::guard('web')->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect()->route('login');
	}
}
