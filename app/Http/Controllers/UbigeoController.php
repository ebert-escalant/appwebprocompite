<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

	public function addProvince(Request $request)
	{
		if ($request->isMethod('post')) {
			try
			{
				DB::beginTransaction();

				$errors = AppHelper::validate(
					[
						'department_id' => trim($request->input('txtDepartmentId')),
						'province' => trim($request->input('txtProvince'))
					],
					[
						'department_id' => 'required',
						'province' => 'required'
					]
				);

				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('ubigeo.add-province'), AppHelper::ERROR, $errors);
				}

				$provinces = file_get_contents(public_path('data/provinces.json'));

				$provinces = json_decode($provinces, true);

				$province = collect($provinces)->first(function ($item) use ($request) {
					return strtolower($item['name']) == strtolower($request->province);
				});


				if (!empty($province)) {
					DB::rollBack();

					return AppHelper::redirect(route('dashboard'), AppHelper::ERROR, ['La provincia ya existe']);
				}

				$lastProvince = collect($provinces)->where('department_id', $request->department_id)->sortByDesc('id')->first();

				$lastUbigeo = $lastProvince ? (int)substr($lastProvince['id'], 2, 2) : 0;

				$newUbigeo = str_pad($request->department_id, 2, '0', STR_PAD_LEFT) . str_pad($lastUbigeo + 1, 2, '0', STR_PAD_LEFT);

				$province = [
					'id' => $newUbigeo,
					'name' => $request->province,
					'department_id' => $request->department_id
				];

				$provinces[] = $province;

				file_put_contents(public_path('data/provinces.json'), json_encode($provinces, JSON_PRETTY_PRINT));

				DB::commit();

				return AppHelper::redirect(route('dashboard'), AppHelper::SUCCESS, ['Provincia agregada con éxito']);

			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('dashboard'));
			}
		}

		$departments = AppHelper::getDepartments();

		return view('ubigeo.add-province', ['departments' => $departments]);
	}

	public function addDistrict(Request $request)
	{
		if ($request->isMethod('post')) {
			try
			{
				DB::beginTransaction();

				$errors = AppHelper::validate(
					[
						'province_id' => trim($request->input('txtProvinceId')),
						'district' => trim($request->input('txtDistrict'))
					],
					[
						'province_id' => 'required',
						'district' => 'required'
					]
				);

				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('dashboard'), AppHelper::ERROR, $errors);
				}

				$districts = file_get_contents(public_path('data/districts.json'));

				$districts = json_decode($districts, true);

				$district = collect($districts)->first(function ($item) use ($request) {
					return strtolower($item['name']) == strtolower($request->district);
				});

				if (!empty($district)) {
					DB::rollBack();

					return AppHelper::redirect(route('dashboard'), AppHelper::ERROR, ['El distrito ya existe']);
				}

				$lastDistrict = collect($districts)->where('province_id', $request->province_id)->sortByDesc('id')->first();

				$lastUbigeo = $lastDistrict ? (int)substr($lastDistrict['id'], 4, 2) : 0;

				$newUbigeo = str_pad($request->province_id, 4, '0', STR_PAD_LEFT) . str_pad($lastUbigeo + 1, 2, '0', STR_PAD_LEFT);

				$district = [
					'id' => $newUbigeo,
					'name' => $request->district,
					'province_id' => $request->province_id,
					'department_id' => substr($request->province_id, 0, 2)
				];

				$districts[] = $district;

				file_put_contents(public_path('data/districts.json'), json_encode($districts, JSON_PRETTY_PRINT));

				DB::commit();

				return AppHelper::redirect(route('dashboard'), AppHelper::SUCCESS, ['Distrito agregado con éxito']);

			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('dashboard'));
			}
		}

		$departments = AppHelper::getDepartments();

		return view('ubigeo.add-district', ['departments' => $departments]);
	}
}
