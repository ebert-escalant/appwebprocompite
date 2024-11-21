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
        $search = $request->input('search') ?? '';
        $data = Partner::where('dni', 'like', '%' . $search . '%')
            ->orWhere('full_name','like','%' . $search . '%')
            ->orWhere('birthdate','like','%' . $search . '%')
            ->orWhere('phone','like','%' . $search . '%')
            ->orWhere('address','like','%' . $search . '%')
            ->orWhere('email','like','%' . $search . '%')
            ->orWhere('family_charge','like','%' . $search . '%')
            ->orWhere('charge','like','%' . $search . '%')
            ->paginate(10);

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

                    return AppHelper::redirect(route('partners.insert'), AppHelper::ERROR, $errors);
                }

                $partner = new Partner();
                $partner->id = uniqid();
                $partner->dni = $request->input('txtDni');
                $partner->full_name = $request->input('txtFullName');
                $partner->birthdate = $request->input('txtBirthdate');
                $partner->phone = $request->input('txtPhone');
                $partner->address = $request->input('txtAddress');
                $partner->email = $request->input('txtEmail');
                $partner->family_charge = $request->input('txtFamilyCharge');
                $partner->charge = $request->input('txtCharge');
                $partner->save();

                DB::commit();

                return AppHelper::redirect(route('partners.index'), AppHelper::SUCCESS, ['Registro exitoso']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirect(route('partners.insert'), AppHelper::ERROR, [$e->getMessage()]);
            }
        }
        return view('partners.insert');
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