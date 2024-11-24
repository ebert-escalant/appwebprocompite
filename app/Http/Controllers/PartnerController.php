<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function getAll(Request $request)
    {
        $search = $request->input('search') ? $request->input('search') : '';

        $data = Partner::whereRaw('concat(dni, full_name, phone, address, email, family_charge, charge) like ?', ['%' . $search . '%']) ->paginate(10);

        $data->appends(['search' => $search]);
        $data->onEachSide(0);
        return view('partners.index', ['data' => $data, 'search' => $search]);
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
                        'family_charge' => trim($request->input('txtFamilyCharge')),
                        'charge' => trim($request->input('txtCharge')),
						'spouse_dni' => trim($request->input('txtSpouseDni')),
						'has_spouse' => trim($request->input('chkHasSpouse')),
						'spouse_full_name' => trim($request->input('txtSpouseFullName')),
						'spouse_birthdate' => trim($request->input('txtSpouseBirthDate')),
						'spouse_phone' => trim($request->input('txtSpousePhone')),
						'spouse_email' => trim($request->input('txtSpouseEmail'))
                    ],
                    [
                        'dni' => ['required', 'string', 'max:8', 'unique:partners,dni'],
                        'full_name' => ['required', 'string', 'max:255'],
                        'birthdate' => ['required', 'date_format:Y-m-d'],
                        'phone' => ['required', 'string', 'max:13'],
                        'address' => ['required', 'string', 'max:255'],
                        'email' => ['nullable', 'string', 'max:255'],
                        'family_charge' => ['required', 'string', 'max:255'],
                        'charge' => ['required', 'string', 'max:255'],
						'spouse_dni' => ['required_if:has_spouse,true', 'string', 'max:8'],
						'spouse_full_name' => ['required_if:has_spouse,true', 'string', 'max:255'],
						'spouse_birthdate' => ['required_if:has_spouse,true', 'date_format:Y-m-d'],
						'spouse_phone' => ['required_if:has_spouse,true', 'string', 'max:13'],
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
                $partner->phone = trim($request->input('txtPhone'));
                $partner->address = trim($request->input('txtAddress'));
                $partner->email = trim($request->input('txtEmail')) ? trim($request->input('txtEmail')) : '';
                $partner->family_charge = trim($request->input('txtFamilyCharge'));
                $partner->charge = $request->input('txtCharge');

				if ($request->input('chkHasSpouse')) {
					$partner->spouse = [
						'dni' => trim($request->input('txtSpouseDni')),
						'full_name' => trim($request->input('txtSpouseFullName')),
						'birthdate' => trim($request->input('txtSpouseBirthDate')),
						'phone' => trim($request->input('txtSpousePhone')),
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
                        'birthdate' => trim($request->input('txtBirthdate')),
                        'phone' => trim($request->input('txtPhone')),
                        'address' => trim($request->input('txtAddress')),
                        'email' => trim($request->input('txtEmail')),
                        'family_charge' => trim($request->input('txtFamilyCharge')),
                        'charge' => trim($request->input('txtCharge'))
                    ],
                    [
                        'dni' => ['required', 'string', 'max:8'],
                        'full_name' => ['required', 'string', 'max:255'],
                        'birthdate' => ['required', 'date_format:Y-m-d'],
                        'phone' => ['required', 'string', 'max:13'],
                        'address' => ['required', 'string', 'max:255'],
                        'email' => ['required', 'string', 'max:255'],
                        'family_charge' => ['required', 'string', 'max:255'],
                        'charge' => ['required', 'string', 'max:255']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('partners.edit', ['id' => $id]), AppHelper::ERROR, $errors);
                }

                $partner->dni = $request->input('txtDni');
                $partner->full_name = $request->input('txtFullName');
                $partner->birthdate = $request->input('txtBirthdate');
                $partner->phone = $request->input('txtPhone');
                $partner->address = $request->input('txtAddress');
                $partner->email = $request->input('txtEmail');
                $partner->family_charge = $request->input('txtFamilyCharge');
                $partner->charge = $request->input('txtCharge');
                if($request->input('chkHasSpouse')){
                    $partner->spouse = [
						'dni' => trim($request->input('txtSpouseDni')),
						'full_name' => trim($request->input('txtSpouseFullName')),
						'birthdate' => trim($request->input('txtSpouseBirthDate')),
						'phone' => trim($request->input('txtSpousePhone')),
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