<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Partner;
use App\Models\Project;
use App\Models\ProjectMember;
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

        $data = Project::with('society:id,social_razon')->whereRaw('concat(name, name, category) like ? and (year=? or "all"=?)', ['%' . $search . '%', $year, $year])->orderBy('created_at', 'desc')->paginate(10);

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
			$hasMembers = ProjectMember::where('project_id', $id)->count();

			if ($hasMembers > 0) {
				DB::rollBack();
				return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['No se puede eliminar el proyecto por que tiene miembros registrados. Primero elimine los miembros de este proyecto.']);
			}

            if ($item == null) {
                DB::rollBack();

                return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
            }
			if($item->file) {
				Storage::disk('local')->delete('projects/'.$item->file->filename);
			}
			if($item->assets_file)
			{
				Storage::disk('local')->delete('projects/assets/'.json_decode($item->assets_file)->filename);
			}
            $item->delete();

            DB::commit();

            return AppHelper::redirect(route('projects.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
        } catch (\Exception $e) {
            DB::rollBack();
			return dd($e->getMessage());
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
				if ($request->hasFile('fileUploadFileAssets')) {
					$errors = AppHelper::validate(
						[
							'fileUploadFileAssets' => $request->hasFile('fileUploadFileAssets') ? $request->fileUploadFileAssets : null,
						],
						[
							'fileUploadFileAssets' => ['required', 'file', 'mimes:pdf', 'max:20480'],
						],
						[
							'fileUploadFileAssets.file' => 'El archivo debe ser un archivo.',
							'fileUploadFileAssets.mimes' => 'El archivo debe ser un archivo PDF.',
							'fileUploadFileAssets.max' => 'El archivo no debe ser mayor a 20MB.'
						]
					);

					if (count($errors) > 0) {
						DB::rollBack();
						//retornar json con error
						return response()->json(['status' => 'error', 'messages' => $errors], 422);
					}

					if ($item->assets_file) {
						// delete old file
						try {
							Storage::disk('local')->delete('projects/assets/'.json_decode($item->assets_file)->filename);
						} catch (\Exception $e) {
							return dd($e->getMessage());
						}
					}

					$file = $request->file('fileUploadFileAssets');

					$filename = uniqid();

					Storage::disk('local')->put('projects/assets/'.$filename.'.'.strtolower($file->getClientOriginalExtension()), file_get_contents($file));

					$item->assets_file = [
						'originalname' => $file->getClientOriginalName(),
						'filename' => $filename.'.'.strtolower($file->getClientOriginalExtension()),
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				if ($assets && is_array($assets)) {
					foreach ($assets as $asset) {
						$newAssets[] = [
							'description' => $asset['description'],
							'quantity' => $asset['quantity'],
							'unit' => $asset['unit'],
							'receptionDate' => $asset['receptionDate'],
							'type' => $asset['type'],
							'status' => $asset['status'],
							'observation' => isset($asset['observation']) ? $asset['observation'] : '',
							'file' => isset($asset['file']) ? $asset['file'] : null,
							'amount' => isset($asset['amount']) ? $asset['amount'] : 0
						];
					}
				}

				$item->assets = $newAssets;

				$item->save();

				DB::commit();
				//retornar json con exito y archivo campo para validar que se agrego archivo
				return response()->json(['status' => 'success', 'data' => $item, 'messages' => ['Operación realizada con éxito.']]);

				// return AppHelper::redirect(route('projects.index'), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
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
						'rating' => ['nulleable', 'numeric', 'min:0', 'max:20'],
						'fileUploadFile' => ['required_if:file_required,true'],
						'liquidation' => ['required', 'boolean']
					],
					[
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

	public function downloadFileAsset($id) {
		$item = Project::find($id);

		if (!$item) {
			return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		if (!$item->assets_file) {
			return AppHelper::redirect(route('projects.index'), AppHelper::ERROR, ['Archivo no encontrado.']);
		}

		$filepath = storage_path('app/private/projects/assets/'.json_decode($item->assets_file)->filename);
		return response()->download($filepath, json_decode($item->assets_file)->originalname);
	}

	public function getMembers($id) {
		$project = Project::find($id);

		if (!$project) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		$members = ProjectMember::where('project_id', $id)->with('member')->orderBy('created_at', 'desc')->get();

		return view('projects.members', ['members' => $members, 'project_id' => $id]);
	}

	public function addMember(Request $request, $id) {
		try {
			DB::beginTransaction();

			$errors = AppHelper::validate(
				[
					'dni' => trim($request->input('txtDni'))
				],
				[
					'dni' => ['required', 'string', 'max:8']
				]
			);

			if (count($errors) > 0) {
				DB::rollBack();

				return response()->json(['status' => 'error', 'messages' => $errors]);
			}

			$member = Partner::where('dni', trim($request->input('txtDni')))->first();

			if (!$member) {
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

					return response()->json(['status' => 'error', 'messages' => $errors]);
                }

                $member = new Partner();
                $member->id = uniqid();
                $member->dni = trim($request->input('txtDni'));
                $member->full_name = trim($request->input('txtFullName'));
                $member->birthdate = trim($request->input('txtBirthDate'));
                $member->phone = trim($request->input('txtPhone')) ? trim($request->input('txtPhone')) : '';
                $member->address = trim($request->input('txtAddress')) ? trim($request->input('txtAddress')) : '';
                $member->email = trim($request->input('txtEmail')) ? trim($request->input('txtEmail')) : '';
                $member->charge = $request->input('txtCharge');

				if ($request->input('chkHasSpouse')) {
					$member->spouse = [
						'dni' => trim($request->input('txtSpouseDni')),
						'full_name' => trim($request->input('txtSpouseFullName')),
						'birthdate' => trim($request->input('txtSpouseBirthDate')),
						'phone' => trim($request->input('txtSpousePhone')) ? trim($request->input('txtSpousePhone')) : '',
						'email' => trim($request->input('txtSpouseEmail')) ? trim($request->input('txtSpouseEmail')) : ''
					];
				}

                $member->save();
			}

			$project = Project::find($id);

			// verificar si el socio ya es miembro de algún proyecto en el año seleccionado
			$exist = ProjectMember::whereRaw('partner_id=?', [$member->id])->whereHas('project', function ($query) use ($project) {
				$query->where('year', $project->year);
			})->count();

			if ($exist > 0) {
				DB::rollBack();

				return response()->json(['status' => 'error', 'messages' => ['El socio ya se encuentra registrado en un proyecto en el año seleccionado.']]);
			}

			// verificar si el socio es esposo(a) de un miembro
			$memberspuse = Partner::whereRaw("JSON_EXTRACT(spouse, '$.dni')=?", [trim($request->input('txtDni'))])->first();

			if ($memberspuse) {
				$existinyear = ProjectMember::whereRaw('partner_id=?', [$memberspuse->id])->whereHas('project', function ($query) use ($project) {
					$query->where('year', $project->year);
				})->first();

				// verificar si el esposo(a) es miembro de una organización en el año seleccionado
				if ($existinyear) {
					DB::rollBack();

					return response()->json(['status' => 'error', 'messages' => ["No se puede agregar el socio por que su esposo(a) ya es miembro de la organización {$existinyear->project->society->social_razon} en el año seleccionado."]]);
				}
			}

			// verificar si el socio tiene esposo(a)
			if ($member->spouse) {
				$spouse = Partner::whereRaw('dni=?', [$member->spouse->dni])->first();

				// verificar si el esposo(a) es miembro
				if($spouse) {
					$existmember = ProjectMember::whereRaw('partner_id=?', [$spouse->id])->whereHas('project', function ($query) use ($project) {
						$query->where('year', $project->year);
					})->first();

					// verificar si el esposo(a) es miembro de una organización en el año seleccionado
					if ($existmember) {
						DB::rollBack();

						return response()->json(['status' => 'error', 'messages' => ["No se puede agregar el socio por que su esposo(a) ya es miembro de la organización {$existmember->project->society->social_razon} en el año seleccionado."]]);
					}
				}
			}

			$item = new ProjectMember();
			$item->id = uniqid();
			$item->project_id = $id;
			$item->partner_id = $member->id;
			$item->observation = trim($request->input('txtObservation')) ? trim($request->input('txtObservation')) : '';

			$item->save();

			DB::commit();

			return response()->json(['status' => 'success', 'data' => ProjectMember::with('member')->find($item->id), 'messages' => ['Operación realizada con éxito.']]);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json(['status' => 'error', 'messages' => ['Ocurrió un error al agregar el registro, intente nuevamente.']]);
		}
	}

	public function deleteMember($id) {// ajax
		try {
			DB::beginTransaction();

			$item = ProjectMember::find($id);

			if (!$item) {
				DB::rollBack();

				return response()->json(['status' => 'error', 'messages' => ['Registro no encontrado.']]);
			}

			$item->delete();

			DB::commit();

			return response()->json(['status' => 'success', 'messages' => ['Operación realizada con éxito.']]);
		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json(['status' => 'error', 'messages' => ['Ocurrió un error al eliminar el registro, intente nuevamente.']]);
		}
	}
}
