<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Project;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function getAll(Request $request)
    {
        $search = trim($request->input('search')) ? trim($request->input('search')) : '';
		$year = trim($request->input('year')) ? trim($request->input('year')) : 'all';

        $data = Project::whereRaw('concat(name, name, category) like ? and (year=? or "all"=?)', ['%' . $search . '%', $year, $year])->orderBy('created_at', 'desc')->paginate(10);

        $data->appends(['search' => $search, 'year' => $year]);
        $data->onEachSide(0);
        return view('projects.index', ['data' => $data, 'search' => $search, 'years' => range(date('Y'), 2021, -1), 'year' => $year]);
    }

    public function insert(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                DB::beginTransaction();
                $errors = AppHelper::validate(
                    [
                        'name' => trim($request->input('txtPlanNegocio')),
                        'category' => trim($request->input('txtCategory')),
                        'investment_amount' => trim($request->input('txtAmountInversment')),
                        'cofinance_amount' => trim($request->input('txtConfinanceAmount')),
						'year' => trim($request->input('txtYear')),
						'society_id' => $request->input('txtSociety')
					],
					[
						'name' => ['required', 'string', 'max:700'],
                        'category' => ['required', 'string', 'max:255'],
                        'investment_amount' => ['required', 'numeric'],
                        'cofinance_amount' => ['required', 'numeric'],
						'year' => ['required', 'numeric'],
						'society_id' => ['required', 'exists:societies,id']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('projects.insert'), AppHelper::ERROR, $errors);
                }

				$existInYear = Project::whereRaw('year = ? and society_id = ?', [trim($request->input('txtYear')), $request->input('txtSociety')])->count();

				if ($existInYear > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.insert'), AppHelper::ERROR, ['Ya existe un proyecto con el mismo año y organización.']);
				}

                $item = new Project();
                $item->id = uniqid();
                $item->name = trim($request->input('txtPlanNegocio'));
                $item->category = trim($request->input('txtCategory'));
                $item->investment_amount = trim($request->input('txtAmountInversment'));
                $item->cofinance_amount = trim($request->input('txtConfinanceAmount'));
                $item->year = trim($request->input('txtYear'));
                $item->society_id = $request->input('txtSociety');
				$item->liquidation = false;
                $item->qualification = 0;
				$item->assets = null;
				$item->file = null;

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('projects.insert'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.insert'));
            }
        }

		$societies = Society::orderBy('social_razon', 'asc')->get();

        return view('projects.insert', ['societies' => $societies, 'years' => range(date('Y'), 2021, -1)]);
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
                        'category' => trim($request->input('txtCategory')),
                        'investment_amount' => trim($request->input('txtAmountInversment')),
                        'cofinance_amount' => trim($request->input('txtConfinanceAmount')),
						'year' => trim($request->input('txtYear')),
						'society_id' => $request->input('txtSociety')
					],
					[
						'name' => ['required', 'string', 'max:700'],
                        'category' => ['required', 'string', 'max:255'],
                        'investment_amount' => ['required', 'numeric'],
                        'cofinance_amount' => ['required', 'numeric'],
						'year' => ['required', 'numeric'],
						'society_id' => ['required', 'exists:societies,id']
                    ]
                );

                if (count($errors) > 0) {
                    DB::rollBack();

                    return AppHelper::redirect(route('projects.edit', $item->id), AppHelper::ERROR, $errors);
                }

				$existInYear = Project::whereRaw('id != ? and year = ? and society_id = ?', [$item->id, trim($request->input('txtYear')), $request->input('txtSociety')])->count();

				if ($existInYear > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.edit', $item->id), AppHelper::ERROR, ['Ya existe un proyecto con el mismo año y organización.']);
				}

                $item->name = trim($request->input('txtPlanNegocio'));
                $item->category = trim($request->input('txtCategory'));
                $item->investment_amount = trim($request->input('txtAmountInversment'));
                $item->cofinance_amount = trim($request->input('txtConfinanceAmount'));
				$item->year = trim($request->input('txtYear'));
				$item->society_id = $request->input('txtSociety');

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('projects.edit', $item->id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.edit', $item->id));
            }
        }

        $item = Project::find($id);

        if (!$item) {
            return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
        }

		$societies = Society::orderBy('social_razon', 'asc')->get();

        return view('projects.edit', ['project' => $item, 'societies' => $societies, 'years' => range(date('Y'), 2021, -1)]);
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

	public function editAssets(Request $request, $id) {
		if ($request->isMethod('put')) {
			try {
				DB::beginTransaction();

				$item = Project::find($id);

				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
				}

				$errors = AppHelper::validate(
					[
						'assets' => trim($request->input('txtAssets'))
					],
					[
						'assets' => ['nullable', 'string']
					]
				);

				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, $errors);
				}

				$assets = json_decode(trim($request->input('txtAssets')), true);
				$newAssets = null;

				if ($assets && is_array($assets)) {
					foreach ($assets as $asset) {
						$newAssets[] = [
							'description' => $asset['description'],
							'quantity' => $asset['quantity'],
							'unit' => $asset['unit'],
							'receptionDate' => $asset['receptionDate'],
							'type' => $asset['type'],
							'status' => $asset['status'],
							'observation' => isset($asset['observation']) ? $asset['observation'] : ''
						];
					}
				}

				$item->assets = $newAssets;

				$item->save();

				DB::commit();

				return AppHelper::redirect(route('projects.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.index'));
			}
		}

		$item = Project::find($id);

		if (!$item) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		return view('projects.edit-assets', ['project' => $item]);
	}

	public function editQualification(Request $request, $id) {
		if ($request->isMethod('put')) {
			try {
				DB::beginTransaction();

				$item = Project::find($id);

				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
				}

				$errors = AppHelper::validate(
					[
						'rating' => trim($request->input('rating')),
						'fileUploadFile' => $request->hasFile('fileUploadFile') ? $request->fileUploadFile : null,
						'file_required' => false,
						'liquidation' => $request->input('txtLiquidation')
					],
					[
						'rating' => ['required', 'numeric', 'min:0', 'max:20'],
						'fileUploadFile' => ['required_if:file_required,true'],
						'liquidation' => ['required', 'boolean']
					],
					[
						'rating.required' => 'La calificación es obligatoria.',
						'fileUploadFile.required_if' => 'El archivo es obligatorio.',
						'liquidation.required' => 'La liquidación es obligatoria.'
					]
				);

				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, $errors);
				}

				if ($request->hasFile('fileUploadFile')) {
					$errors = AppHelper::validate(
						[
							'fileUploadFile' => $request->hasFile('fileUploadFile') ? $request->fileUploadFile : null,
						],
						[
							'fileUploadFile' => ['required', 'file', 'mimes:pdf', 'max:20480'],
						],
						[
							'fileUploadFile.file' => 'El archivo debe ser un archivo.',
							'fileUploadFile.mimes' => 'El archivo debe ser un archivo PDF.',
							'fileUploadFile.max' => 'El archivo no debe ser mayor a 20MB.'
						]
					);
	
					if (count($errors) > 0) {
						DB::rollBack();
	
						return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, $errors);
					}

					if ($item->file) {
						// delete old file
						Storage::disk('local')->delete('projects/'.$item->file->filename);
					}

					$file = $request->file('fileUploadFile');

					$filename = uniqid();

					Storage::disk('local')->put('projects/'.$filename.'.'.strtolower($file->getClientOriginalExtension()), file_get_contents($file));

					$item->file = [
						'originalname' => $file->getClientOriginalName(),
						'filename' => $filename.'.'.strtolower($file->getClientOriginalExtension()),
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$item->qualification = trim($request->input('rating'));
				$item->liquidation = $request->input('txtLiquidation');

				$item->save();

				DB::commit();

				return AppHelper::redirect(route('projects.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('projects.index'));
			}
		}

		$item = Project::find($id);

		if (!$item) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		return view('projects.edit-qualification', ['project' => $item]);
	}

	public function downloadFile($id) {
		$item = Project::find($id);

		if (!$item) {
			return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		if (!$item->file) {
			return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Archivo no encontrado.']);
		}

		$filepath = storage_path('app/private/projects/'.$item->file->filename);

		return response()->download($filepath, $item->file->originalname);
	}
}
