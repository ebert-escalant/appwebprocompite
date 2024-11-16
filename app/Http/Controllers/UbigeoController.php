<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UbigeoController extends Controller
{
	public function getDepartments(Request $request)
	{
		$json = file_get_contents(public_path('data/departments.json'));

		$data = json_decode($json, true);

		return response()->json($data);
	}

	public function getProvinces($departement)
	{
		$departments = file_get_contents(public_path('data/departments.json'));


		$departments = json_decode($departments, true);

		$department = array_filter($departments, function ($item) use ($departement) {
			return strtolower($item['name']) == strtolower($departement);
		});

		if (empty($department)) {
			return response()->json(['error' => 'Departamento no encontrado'], 404);
		}

		$departement = array_values($department);

		$json = file_get_contents(public_path('data/provinces.json'));

		$data = json_decode($json, true);

		$data = array_filter($data, function ($item) use ($departement) {
			return $item['department_id'] == $departement[0]['id'];
		});

		return response()->json(array_values($data));
	}

	public function getDistricts($province)
	{
		$provinces = file_get_contents(public_path('data/provinces.json'));

		$provinces = json_decode($provinces, true);

		$province = array_filter($provinces, function ($item) use ($province) {
			return strtolower($item['name']) == strtolower($province);
		});

		if (empty($province)) {
			return response()->json(['error' => 'Provincia no encontrada'], 404);
		}

		$province = array_values($province);

		$json = file_get_contents(public_path('data/districts.json'));

		$data = json_decode($json, true);

		$data = array_filter($data, function ($item) use ($province) {
			return $item['province_id'] == $province[0]['id'];
		});

		return response()->json(array_values($data));
	}
}
