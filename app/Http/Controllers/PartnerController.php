<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Partner;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function getAll(Request $request)
    {
        $search = $request->input('search') ? $request->input('search') : '';
		$year = $request->input('year') ? $request->input('year') : 'all';

        $data = Partner::with(['projectMembers' => function($query) use($year) {
			if ($year != 'all') {
				$query->whereHas('project', function($query) use($year) {
					$query->where('year', $year);
				});
			}
		}])->where(function($query) use ($search, $year) {
			$query->whereRaw('concat(dni, full_name, phone, address, email, charge) like ?', ['%' . $search . '%']);
			if ($year != 'all') {
				$query->whereHas('projectMembers', function($query) use($year) {
					$query->whereHas('project', function($query) use($year) {
						$query->where('year', $year);
					});
				});
			}
		})->paginate(10);

		if ($year != 'all') {
			foreach ($data as $partner) {
				$partner->project = $partner->projectMembers->count() > 0 ? $partner->projectMembers->first()->project : null;
			}
		}

        $data->appends(['search' => $search, 'year' => $year]);

        return view('partners.index', ['data' => $data, 'search' => $search, 'year' => $year, 'years' => range(date('Y'), 2021, -1)]);
    }

    public function insert(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                DB::beginTransaction();

                $errors = AppHelper::validate(
                    [
                        'dni' => trim($request->input('txtDni')),
                        'full_name' => trim($request->input('txtFullName')),
                        'birthdate' => trim($request->input('txtBirthDate')),
                        'phone' => trim($request->input('txtPhone')),
                        'address' => trim($request->input('txtAddress')),
                        'email' => trim($request->input('txtEmail')),
                        'charge' => trim($request->input('txtCharge')),
						'has_spouse' => trim($request->input('chkHasSpouse')),
						'spouse_dni' => trim($request->input('txtSpouseDni')),
						'spouse_full_name' => trim($request->input('txtSpouseFullName')),
						'spouse_birthdate' => trim($request->input('txtSpouseBirthDate')),
						'spouse_phone' => trim($request->input('txtSpousePhone')),
						'spouse_email' => trim($request->input('txtSpouseEmail'))
                    ],
                    [
                        'dni' => ['required', 'string', 'max:8', 'unique:partners,dni'],
                        'full_name' => ['required', 'string', 'max:255'],
                        'birthdate' => ['required', 'date_format:Y-m-d'],
                        'phone' => ['nullable', 'string', 'max:9'],
                        'address' => ['nullable', 'string', 'max:255'],
                        'email' => ['nullable', 'string', 'max:255'],
                        'charge' => ['required', 'string', 'max:255'],
						'spouse_dni' => ['required_if:has_spouse,true', 'string', 'max:8'],
						'spouse_full_name' => ['required_if:has_spouse,true', 'string', 'max:255'],
						'spouse_birthdate' => ['required_if:has_spouse,true', 'date_format:Y-m-d'],
						'spouse_phone' => ['nullable', 'string', 'max:9'],
						'spouse_email' => ['nullable', 'string', 'max:255']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('partners.insert'), AppHelper::ERROR, $errors);
                }

                $partner = new Partner();
                $partner->id = uniqid();
                $partner->dni = trim($request->input('txtDni'));
                $partner->full_name = trim($request->input('txtFullName'));
                $partner->birthdate = trim($request->input('txtBirthDate'));
                $partner->phone = trim($request->input('txtPhone')) ? trim($request->input('txtPhone')) : '';
                $partner->address = trim($request->input('txtAddress')) ? trim($request->input('txtAddress')) : '';
                $partner->email = trim($request->input('txtEmail')) ? trim($request->input('txtEmail')) : '';
                $partner->charge = $request->input('txtCharge');

				if ($request->input('chkHasSpouse')) {
					$partner->spouse = [
						'dni' => trim($request->input('txtSpouseDni')),
						'full_name' => trim($request->input('txtSpouseFullName')),
						'birthdate' => trim($request->input('txtSpouseBirthDate')),
						'phone' => trim($request->input('txtSpousePhone')) ? trim($request->input('txtSpousePhone')) : '',
						'email' => trim($request->input('txtSpouseEmail')) ? trim($request->input('txtSpouseEmail')) : ''
					];
				}

                $partner->save();

                DB::commit();

                return AppHelper::redirect(route('partners.insert'), AppHelper::SUCCESS, ['Registro exitoso']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirect(route('partners.insert'), AppHelper::ERROR, [$e->getMessage()]);
            }
        }
        return view('partners.insert');
    }

    public function edit(Request $request, $id)
    {
        $partner = Partner::find($id);

        if (!$partner) {
            return AppHelper::redirect(route('partners.index'), AppHelper::ERROR, ['Socio no encontrado']);
        }

        if ($request->isMethod('put')) {
            try {

                DB::beginTransaction();
                $errors = AppHelper::validate(
                    [
                        'dni' => trim($request->input('txtDni')),
                        'full_name' => trim($request->input('txtFullName')),
                        'birthdate' => trim($request->input('txtBirthDate')),
                        'phone' => trim($request->input('txtPhone')),
                        'address' => trim($request->input('txtAddress')),
                        'email' => trim($request->input('txtEmail')),
                        'charge' => trim($request->input('txtCharge')),
						'has_spouse' => trim($request->input('chkHasSpouse')),
						'spouse_dni' => trim($request->input('txtSpouseDni')),
						'spouse_full_name' => trim($request->input('txtSpouseFullName')),
						'spouse_birthdate' => trim($request->input('txtSpouseBirthDate')),
						'spouse_phone' => trim($request->input('txtSpousePhone')),
						'spouse_email' => trim($request->input('txtSpouseEmail'))
                    ],
                    [
                        'dni' => ['required', 'string', 'max:8'],
                        'full_name' => ['required', 'string', 'max:255'],
                        'birthdate' => ['required', 'date_format:Y-m-d'],
                        'phone' => ['nullable', 'string', 'max:9'],
                        'address' => ['nullable', 'string', 'max:255'],
                        'email' => ['nullable', 'string', 'max:255'],
                        'charge' => ['required', 'string', 'max:255'],
						'spouse_dni' => ['required_if:has_spouse,true', 'string', 'max:8'],
						'spouse_full_name' => ['required_if:has_spouse,true', 'string', 'max:255'],
						'spouse_birthdate' => ['required_if:has_spouse,true', 'date_format:Y-m-d'],
						'spouse_phone' => ['nullable', 'string', 'max:9'],
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('partners.edit', ['id' => $id]), AppHelper::ERROR, $errors);
                }

                $partner->dni = trim($request->input('txtDni'));
                $partner->full_name = trim($request->input('txtFullName'));
                $partner->birthdate = trim($request->input('txtBirthDate'));
                $partner->phone = trim($request->input('txtPhone')) ? trim($request->input('txtPhone')) : '';
                $partner->address = trim($request->input('txtAddress')) ? trim($request->input('txtAddress')) : '';
                $partner->email = trim($request->input('txtEmail')) ? trim($request->input('txtEmail')) : '';
                $partner->charge = $request->input('txtCharge');
                if($request->input('chkHasSpouse')){
                    $partner->spouse = [
						'dni' => trim($request->input('txtSpouseDni')),
						'full_name' => trim($request->input('txtSpouseFullName')),
						'birthdate' => trim($request->input('txtSpouseBirthDate')),
						'phone' => trim($request->input('txtSpousePhone')) ? trim($request->input('txtSpousePhone')) : '',
						'email' => trim($request->input('txtSpouseEmail')) ? trim($request->input('txtSpouseEmail')) : ''
					];
                }else{
                    $partner->spouse = null;
                }

                $partner->save();

                DB::commit();

                return AppHelper::redirect(route('partners.index'), AppHelper::SUCCESS, ['Actualización exitosa']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirect(route('partners.edit', ['id' => $id]), AppHelper::ERROR, [$e->getMessage()]);
            }
        }
        return view('partners.edit', ['partner' => $partner]);
    }

    public function delete($id)
    {
        $partner = Partner::find($id);
        if (!$partner) {
            return AppHelper::redirect(route('partners.index'), AppHelper::ERROR, ['Socio no encontrado']);
        }

        $partner->delete();

        return AppHelper::redirect(route('partners.index'), AppHelper::SUCCESS, ['Eliminación exitosa']);
    }

    public function getByDni($dni)
    {
        $partner = Partner::where('dni', $dni)->first();
        if (!$partner) {
            return response()->json(['message' => 'Socio no encontrado'], 404);
        }

        return response()->json($partner);
    }
}