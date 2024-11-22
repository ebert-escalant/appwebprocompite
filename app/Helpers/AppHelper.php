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

	public static function getDepartments()
	{
		$json = file_get_contents(public_path('data/departments.json'));

		$data = json_decode($json, true);

		return $data;
	}

	public static function getProvinces($department)
	{
		$departments = file_get_contents(public_path('data/departments.json'));

		$departments = json_decode($departments, true);

		$department = array_filter($departments, function ($item) use ($department) {
			return strtolower($item['name']) == strtolower($department);
		});

		if (empty($department)) {
			return [];
		}

		$department = array_values($department);

		$json = file_get_contents(public_path('data/provinces.json'));

		$data = json_decode($json, true);

		$data = array_filter($data, function ($item) use ($department) {
			return $item['department_id'] == $department[0]['id'];
		});

		return array_values($data);
	}

	public static function getDistricts($province)
	{
		$provinces = file_get_contents(public_path('data/provinces.json'));

		$provinces = json_decode($provinces, true);

		$province = array_filter($provinces, function ($item) use ($province) {
			return strtolower($item['name']) == strtolower($province);
		});

		if (empty($province)) {
			return [];
		}

		$province = array_values($province);

		$json = file_get_contents(public_path('data/districts.json'));

		$data = json_decode($json, true);

		$data = array_filter($data, function ($item) use ($province) {
			return $item['province_id'] == $province[0]['id'];
		});

		return array_values($data);
	}

	public static function getYears()
	{
		return range(date('Y'), 2018, -1);
		$years = [];
		$currentYear = date('Y');

		for ($i = 2018; $i <= $currentYear; $i++) {
			$years[] = $i;
		}

		return $years;
	}
}
