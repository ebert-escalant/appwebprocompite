<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocietyController extends Controller
{
    public function getAll(Request $request)
    {
        $search = $request->input('search') ?? '';
        $data = Society::whereRaw('concat(type, social_razon, ruc) like ?', ['%' . $search . '%'])->paginate(10);
        $data->appends(['search' => $search]);
        $data->onEachSide(0);

        return view('societies.index', ['data' => $data, 'search' => $search]);
    }

    public function insert(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                DB::beginTransaction();

                $errors = AppHelper::validate(
                    [
                        'type' => trim($request->input('txtType')),
                        'social_razon' => trim($request->input('txtSocialRazon')),
                        'ruc' => trim($request->input('txtRuc')),
						'constitution_date' => trim($request->input('txtConstitutionDate')),
						'part_number' => trim($request->input('txtPartNumber')),
						'district' => trim($request->input('txtDistrict')),
						'province' => trim($request->input('txtProvince')),
						'department' => trim($request->input('txtDepartment')),
						'comunity' => trim($request->input('txtComunity')),
						'address' => trim($request->input('txtAddress')),
						'phone' => trim($request->input('txtPhone')),
						'latitude' => trim($request->input('txtLatitude')),
						'longitude' => trim($request->input('txtLongitude')),
						'email' => trim($request->input('txtEmail')),
					],
					[
						'type' => ['required', 'string', 'max:30'],
						'social_razon' => ['required', 'string', 'max:255'],
						'ruc' => ['required', 'string', 'max:11'],
						'constitution_date' => ['required', 'date_format:Y-m-d'],
						'part_number' => ['required', 'integer'],
						'district' => ['required', 'string', 'max:255'],
						'province' => ['required', 'string', 'max:255'],
						'department' => ['required', 'string', 'max:255'],
						'comunity' => ['required', 'string', 'max:255'],
						'address' => ['required', 'string', 'max:255'],
						'phone' => ['required', 'string', 'max:13'],
						'latitude' => ['required', 'string', 'max:255'],
						'longitude' => ['required', 'string', 'max:255'],
						'email' => ['required', 'string', 'max:255'],
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.insert'), AppHelper::ERROR, $errors);
                }

                $item = new Society();
                $item->id = uniqid();
                $item->type = trim($request->input('txtType'));
				$item->social_razon = trim($request->input('txtSocialRazon'));
				$item->ruc = trim($request->input('txtRuc'));
				$item->constitution_date = trim($request->input('txtConstitutionDate'));
				$item->part_number = trim($request->input('txtPartNumber'));
				$item->district = trim($request->input('txtDistrict'));
				$item->province = trim($request->input('txtProvince'));
				$item->department = trim($request->input('txtDepartment'));
				$item->comunity = trim($request->input('txtComunity'));
				$item->address = trim($request->input('txtAddress'));
				$item->phone = trim($request->input('txtPhone'));
				$item->latitude = trim($request->input('txtLatitude'));
				$item->longitude = trim($request->input('txtLongitude'));
				$item->email = trim($request->input('txtEmail'));

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('societies.insert'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.insert'));
            }
        }

		$json = file_get_contents(public_path('data/departments.json'));

		$data = json_decode($json, true);

        return view('societies.insert', ['departments' => $data]);
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            try {
                DB::beginTransaction();

                $item = Society::find($id);

                if ($item == null) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
                }

                $errors = AppHelper::validate(
                    [
                        'type' => trim($request->input('txtType')),
                        'social_razon' => trim($request->input('txtSocialRazon')),
                        'ruc' => trim($request->input('txtRuc')),
						'constitution_date' => trim($request->input('txtConstitutionDate')),
						'part_number' => trim($request->input('txtPartNumber')),
						'district' => trim($request->input('txtDistrict')),
						'province' => trim($request->input('txtProvince')),
						'department' => trim($request->input('txtDepartment')),
						'comunity' => trim($request->input('txtComunity')),
						'address' => trim($request->input('txtAddress')),
						'phone' => trim($request->input('txtPhone')),
						'latitude' => trim($request->input('txtLatitude')),
						'longitude' => trim($request->input('txtLongitude')),
						'email' => trim($request->input('txtEmail')),
					],
					[
						'type' => ['required', 'string', 'max:30'],
						'social_razon' => ['required', 'string', 'max:255'],
						'ruc' => ['required', 'string', 'max:11'],
						'constitution_date' => ['required', 'date_format:Y-m-d'],
						'part_number' => ['required', 'integer'],
						'district' => ['required', 'string', 'max:255'],
						'province' => ['required', 'string', 'max:255'],
						'department' => ['required', 'string', 'max:255'],
						'comunity' => ['required', 'string', 'max:255'],
						'address' => ['required', 'string', 'max:255'],
						'phone' => ['required', 'string', 'max:13'],
						'latitude' => ['required', 'string', 'max:255'],
						'longitude' => ['required', 'string', 'max:255'],
						'email' => ['required', 'string', 'max:255'],
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.edit', $item->id), AppHelper::ERROR, $errors);
                }

                $item->type = trim($request->input('txtType'));
				$item->social_razon = trim($request->input('txtSocialRazon'));
				$item->ruc = trim($request->input('txtRuc'));
				$item->constitution_date = trim($request->input('txtConstitutionDate'));
				$item->part_number = trim($request->input('txtPartNumber'));
				$item->district = trim($request->input('txtDistrict'));
				$item->province = trim($request->input('txtProvince'));
				$item->department = trim($request->input('txtDepartment'));
				$item->comunity = trim($request->input('txtComunity'));
				$item->address = trim($request->input('txtAddress'));
				$item->phone = trim($request->input('txtPhone'));
				$item->latitude = trim($request->input('txtLatitude'));
				$item->longitude = trim($request->input('txtLongitude'));
				$item->email = trim($request->input('txtEmail'));

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('societies.edit', $item->id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();
				dd($e->getMessage());
                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.edit', $item->id));
            }
        }

        $item = Society::find($id);

        if (!$item) {
            return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
        }

		$departments = $this->getDepartments();

		$provinces = $this->getProvinces($item->department);

		$districts = $this->getDistricts($item->province);

		/* return response()->json($provinces); */

        return view('societies.edit', ['society' => $item, 'departments' => $departments, 'provinces' => $provinces, 'districts' => $districts]);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $item = Society::find($id);

            if ($item == null) {
                DB::rollBack();

                return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
            }

            $item->delete();

            DB::commit();

            return AppHelper::redirect(route('societies.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.index'));
        }
    }

	public function getDepartments()
	{
		$json = file_get_contents(public_path('data/departments.json'));

		$data = json_decode($json, true);

		return $data;
	}

	public function getProvinces($department)
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

	public function getDistricts($province)
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
}
