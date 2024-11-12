<?php

namespace App\Helpers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AppHelper
{
	const SUCCESS = 'success';
	const ERROR = 'error';
	const WARNING = 'warning';
	const INFO = 'info';

	public static function redirect($route, $type, $messages = []): RedirectResponse
	{
		Session::flash('type', $type);
		Session::flash('redirectMessages', $messages);

		return redirect($route);
	}

	public static function redirectException($controller, $method, $error, $route): RedirectResponse
	{
		Session::flash('redirectMessages', ['Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde.']);
		Session::flash('type', 'exception');

		return redirect($route);
	}

	public static function validate($request, $rules, $messages = [])
	{
		$validator=Validator::make($request, $rules, $messages);

		if($validator->fails())
		{
			return $validator->errors()->all();
		}

		return [];
	}

	public static function getUUID()
	{
		return Str::uuid()->toString();
	}

	public static function validateFile($file, $mimes, $maxSize)
	{
		$rules = [
			'file' => ['required', 'file', 'mimes:' . $mimes, 'max:' . $maxSize]
		];

		$messages = [
			'file.required' => 'El archivo es obligatorio.',
			'file.file' => 'El archivo debe ser un archivo.',
			'file.mimes' => 'El archivo debe ser de tipo: ' . $mimes,
			'file.max' => 'El archivo no debe superar los ' . $maxSize . ' KB.'
		];

		return AppHelper::validate(['file' => $file], $rules, $messages);
	}

	public static function validateRecaptcha($recaptcha)
	{
		$client = new \GuzzleHttp\Client();
		$response = $client->post(env('RECAPTCHA_SITE'), [
			'form_params' => [
				'secret' => env('RECAPTCHA_SECRET_KEY'),
				'response' => $recaptcha
			]
		]);

		$body = json_decode((string) $response->getBody());

		return $body->success;
	}
}
