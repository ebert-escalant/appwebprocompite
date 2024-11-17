<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function getAll(Request $request)
    {
        $search = $request->input('search') ?? '';
        $data = Project::whereRaw('concat(code, name, category) like ?', ['%' . $search . '%'])->paginate(10);
        $data->appends(['search' => $search]);
        $data->onEachSide(0);
        return view('projects.index', ['data' => $data, 'search' => $search]);
    }

    public function insert(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                DB::beginTransaction();
                $errors = AppHelper::validate(
                    [
                        'name' => trim($request->input('txtPlanNegocio')),
                        'code' => trim($request->input('txtCode')),
                        'category' => trim($request->input('txtCategory')),
                        'investment_amount' => trim($request->input('txtAmountInversment')),
                        'cofinance_amount' => trim($request->input('txtConfinanceAmount')),
					],
					[
						'name' => ['required', 'string', 'max:700'],
                        'code' => ['required', 'string'],
                        'category' => ['required', 'string', 'max:255'],
                        'investment_amount' => ['required', 'numeric'],
                        'cofinance_amount' => ['required', 'numeric'],
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('projects.insert'), AppHelper::ERROR, $errors);
                }

                $item = new Project();
                $item->id = uniqid();
                $item->name = trim($request->input('txtPlanNegocio'));
                $item->code = trim($request->input('txtCode'));
                $item->category = trim($request->input('txtCategory'));
                $item->investment_amount = trim($request->input('txtAmountInversment'));
                $item->cofinance_amount = trim($request->input('txtConfinanceAmount'));

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('projects.insert'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.insert'));
            }
        }

        return view('projects.insert');
    }

    public function edit(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            try {
                DB::beginTransaction();

                $item = Project::find($id);

                if ($item == null) {
                    DB::rollBack();

                    return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
                }

                $errors = AppHelper::validate(
                    [
                        'name' => trim($request->input('txtPlanNegocio')),
                        'code' => trim($request->input('txtCode')),
                        'category' => trim($request->input('txtCategory')),
                        'investment_amount' => trim($request->input('txtAmountInversment')),
                        'cofinance_amount' => trim($request->input('txtConfinanceAmount')),
					],
					[
						'name' => ['required', 'string', 'max:700'],
                        'code' => ['required', 'string'],
                        'category' => ['required', 'string', 'max:255'],
                        'investment_amount' => ['required', 'numeric'],
                        'cofinance_amount' => ['required', 'numeric'],
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('projects.edit', $item->id), AppHelper::ERROR, $errors);
                }

                $item->name = trim($request->input('txtPlanNegocio'));
                $item->code = trim($request->input('txtCode'));
                $item->category = trim($request->input('txtCategory'));
                $item->investment_amount = trim($request->input('txtAmountInversment'));
                $item->cofinance_amount = trim($request->input('txtConfinanceAmount'));

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('projects.edit', $item->id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();
				dd($e->getMessage());
                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.edit', $item->id));
            }
        }

        $item = Project::find($id);

        if (!$item) {
            return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
        }

        return view('projects.edit', ['project' => $item]);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $item = Project::find($id);

            if ($item == null) {
                DB::rollBack();

                return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
            }

            $item->delete();

            DB::commit();

            return AppHelper::redirect(route('projects.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.index'));
        }
    }
}
