<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Partner;
use App\Models\Society;
use App\Models\SocietyMember;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
						'representative_dni' => trim($request->input('txtRepresentativeDni')),
						'representative_full_name' => trim($request->input('txtRepresentativeFullName')),
						'representative_phone' => trim($request->input('txtRepresentativePhone')),
						'representative_email' => trim($request->input('txtRepresentativeEmail')),
						'representative_charge' => trim($request->input('txtRepresentativeCharge'))
					],
					[
						'type' => ['required', 'string', 'max:30'],
						'social_razon' => ['required', 'string', 'max:255'],
						'ruc' => ['required', 'string', 'max:11'],
						'constitution_date' => ['required', 'date_format:Y-m-d'],
						'part_number' => ['required', 'string', 'max:8'],
						'district' => ['required', 'string', 'max:255'],
						'province' => ['required', 'string', 'max:255'],
						'department' => ['required', 'string', 'max:255'],
						'comunity' => ['nullable', 'string', 'max:255'],
						'address' => ['nullable', 'string', 'max:255'],
						'phone' => ['nullable', 'string', 'max:9'],
						'representative_dni' => ['required', 'string', 'max:8'],
						'representative_full_name' => ['required', 'string', 'max:255'],
						'representative_phone' => ['nullable', 'string', 'max:9'],
						'representative_email' => ['nullable', 'string', 'max:255'],
						'representative_charge' => ['required', 'string', 'max:255']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.insert'), AppHelper::ERROR, $errors);
                }

				$representative = Partner::whereRaw('dni=?', [trim($request->input('txtRepresentativeDni'))])->first();

				if (!$representative) {
					$representative = new Partner();
					$representative->id = uniqid();
					$representative->dni = trim($request->input('txtRepresentativeDni'));
					$representative->full_name = trim($request->input('txtRepresentativeFullName'));
					$representative->birthdate = null;
					$representative->phone = trim($request->input('txtRepresentativePhone')) ? trim($request->input('txtRepresentativePhone')) : '';
					$representative->address = '';
					$representative->email = trim($request->input('txtRepresentativeEmail')) ? trim($request->input('txtRepresentativeEmail')) : '';
					$representative->charge = trim($request->input('txtRepresentativeCharge'));

					$representative->save();
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
				$item->comunity = trim($request->input('txtComunity')) ? trim($request->input('txtComunity')) : '';
				$item->address = trim($request->input('txtAddress')) ? trim($request->input('txtAddress')) : '';
				$item->phone = trim($request->input('txtPhone')) ? trim($request->input('txtPhone')) : '';
				$item->id_partner = $representative->id;

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
						'representative_dni' => trim($request->input('txtRepresentativeDni')),
						'representative_full_name' => trim($request->input('txtRepresentativeFullName')),
						'representative_phone' => trim($request->input('txtRepresentativePhone')),
						'representative_email' => trim($request->input('txtRepresentativeEmail')),
						'representative_charge' => trim($request->input('txtRepresentativeCharge'))
					],
					[
						'type' => ['required', 'string', 'max:30'],
						'social_razon' => ['required', 'string', 'max:255'],
						'ruc' => ['required', 'string', 'max:11'],
						'constitution_date' => ['required', 'date_format:Y-m-d'],
						'part_number' => ['required', 'string', 'max:8'],
						'district' => ['required', 'string', 'max:255'],
						'province' => ['required', 'string', 'max:255'],
						'department' => ['required', 'string', 'max:255'],
						'comunity' => ['nullable', 'string', 'max:255'],
						'address' => ['nullable', 'string', 'max:255'],
						'phone' => ['nullable', 'string', 'max:9'],
						'representative_dni' => ['required', 'string', 'max:8'],
						'representative_full_name' => ['required', 'string', 'max:255'],
						'representative_phone' => ['nullable', 'string', 'max:9'],
						'representative_email' => ['nullable', 'string', 'max:255'],
						'representative_charge' => ['required', 'string', 'max:255']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.edit', $item->id), AppHelper::ERROR, $errors);
                }

				$representative = Partner::whereRaw('dni=?', [trim($request->input('txtRepresentativeDni'))])->first();

				if (!$representative) {
					$representative = new Partner();
					$representative->id = uniqid();
					$representative->dni = trim($request->input('txtRepresentativeDni'));
					$representative->full_name = trim($request->input('txtRepresentativeFullName'));
					$representative->birthdate = null;
					$representative->phone = trim($request->input('txtRepresentativePhone')) ? trim($request->input('txtRepresentativePhone')) : '';
					$representative->address = '';
					$representative->email = trim($request->input('txtRepresentativeEmail')) ? trim($request->input('txtRepresentativeEmail')) : '';
					$representative->charge = trim($request->input('txtRepresentativeCharge'));

					$representative->save();
				}

                $item->type = trim($request->input('txtType'));
				$item->social_razon = trim($request->input('txtSocialRazon'));
				$item->ruc = trim($request->input('txtRuc'));
				$item->constitution_date = trim($request->input('txtConstitutionDate'));
				$item->part_number = trim($request->input('txtPartNumber'));
				$item->district = trim($request->input('txtDistrict'));
				$item->province = trim($request->input('txtProvince'));
				$item->department = trim($request->input('txtDepartment'));
				$item->comunity = trim($request->input('txtComunity')) ? trim($request->input('txtComunity')) : '';
				$item->address = trim($request->input('txtAddress')) ? trim($request->input('txtAddress')) : '';
				$item->phone = trim($request->input('txtPhone')) ? trim($request->input('txtPhone')) : '';
				$item->id_partner = $representative->id;

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('societies.index', $item->id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.edit', $item->id));
            }
        }

        $item = Society::with('representative')->find($id);

        if (!$item) {
            return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
        }

		$departments = AppHelper::getDepartments();

		$provinces = AppHelper::getProvinces($item->department);

		$districts = AppHelper::getDistricts($item->province);

        return view('societies.edit', ['society' => $item, 'departments' => $departments, 'provinces' => $provinces, 'districts' => $districts]);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $item = Society::find($id);

			$hasProjects = Project::where('society_id', $id)->exists();

			if($hasProjects) {
				DB::rollBack();

				return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['No se puede eliminar la organización por que tiene proyectos asociados.']);
			}

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
}