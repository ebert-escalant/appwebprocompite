<?php
namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Partner;
use App\Models\Society;
use App\Models\SocietyMember;
use App\Models\SocietyProject;
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
						'comunity' => ['required', 'string', 'max:255'],
						'address' => ['required', 'string', 'max:255'],
						'phone' => ['required', 'string', 'max:9'],
						'representative_dni' => ['required', 'string', 'max:8'],
						'representative_full_name' => ['required', 'string', 'max:255'],
						'representative_phone' => ['required', 'string', 'max:9'],
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
					$representative->phone = trim($request->input('txtRepresentativePhone'));
					$representative->address = '';
					$representative->email = trim($request->input('txtRepresentativeEmail')) ? trim($request->input('txtRepresentativeEmail')) : '';
					$representative->family_charge = '';
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
				$item->comunity = trim($request->input('txtComunity'));
				$item->address = trim($request->input('txtAddress'));
				$item->phone = trim($request->input('txtPhone'));
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
						'comunity' => ['required', 'string', 'max:255'],
						'address' => ['required', 'string', 'max:255'],
						'phone' => ['required', 'string', 'max:9'],
						'representative_dni' => ['required', 'string', 'max:8'],
						'representative_full_name' => ['required', 'string', 'max:255'],
						'representative_phone' => ['required', 'string', 'max:9'],
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
					$representative->phone = trim($request->input('txtRepresentativePhone'));
					$representative->address = '';
					$representative->email = trim($request->input('txtRepresentativeEmail')) ? trim($request->input('txtRepresentativeEmail')) : '';
					$representative->family_charge = '';
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
				$item->comunity = trim($request->input('txtComunity'));
				$item->address = trim($request->input('txtAddress'));
				$item->phone = trim($request->input('txtPhone'));
				$item->id_partner = $representative->id;

                $item->save();

                DB::commit();

                return AppHelper::redirect(route('societies.edit', $item->id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
            } catch (\Exception $e) {
                DB::rollBack();
				dd($e->getMessage());
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

	public function getMembers(Request $request, $id) {
		$item = Society::find($id);
		$year = $request->input('year') ? $request->input('year') : date('Y');
		$search = $request->input('search') ? $request->input('search') : '';

		if (!$item) {
			return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		$members = $item->societyMembers()->with('member')->whereRaw('year=?', [$year])->whereHas('member', function($query) use ($search) {
			$query->whereRaw('concat(dni, full_name) like ?', ['%' . $search . '%']);
		})->paginate(10);

		$members->appends(['year' => $year]);
		$members->appends(['search' => $search]);

		$members->onEachSide(0);

		return view('societies.members', ['society' => $item, 'members' => $members, 'year' => $year, 'years' => range(date('Y'), 2018, -1), 'search' => $search]);
	}

	public function editMemberAssets(Request $request, $id) {
		if ($request->isMethod('put')) {
			try {
				DB::beginTransaction();
				$item = SocietyMember::find($id);


				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.members', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
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

					return AppHelper::redirect(route('societies.members', $item->society_id), AppHelper::ERROR, $errors);
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
							'status' => $asset['status']
						];
					}
				}

				$item->assets = $newAssets;
				$item->save();

				DB::commit();

				return AppHelper::redirect(route('societies.members', $item->society_id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.members', $item->society_id));
			}
		}

		$item = SocietyMember::find($id);

		if (!$item) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		return view('societies.edit-member-assets', ['member' => $item]);
	}

	public function addMember(Request $request, $id) {
		try {
			DB::beginTransaction();

			$item = Society::find($id);

			if (!$item) {
				DB::rollBack();

				return AppHelper::redirect(route('societies.members', $item->id), AppHelper::ERROR, ['Registro no encontrado.']);
			}

			$errors = AppHelper::validate(
				[
					'year' => trim($request->input('txtYear')),
					'dni' => trim($request->input('txtDni'))
				],
				[
					'year' => ['required', 'numeric'],
					'dni' => ['required', 'string', 'max:8', 'exists:partners,dni']
				]
			);

			if (count($errors) > 0) {
				DB::rollBack();

				return AppHelper::redirect(route('societies.members', $item->id), AppHelper::ERROR, $errors);
			}

			$partner = Partner::where('dni', trim($request->input('txtDni')))->first();

			$existitem = SocietyMember::with('society')->whereRaw('partner_id=? and year=?', [$partner->id, trim($request->input('txtYear'))])->first();

			if ($existitem) {
				DB::rollBack();

				return AppHelper::redirect(route('societies.members', $item->id), AppHelper::ERROR, ["El socio ya es miembro de la organización {$existitem->society->social_razon} en el año seleccionado."]);
			}

			// verificar si el socio es esposo(a) de un miembro
			$memberspuse = Partner::whereRaw("JSON_EXTRACT(spouse, '$.dni')=?", [trim($request->input('txtDni'))])->first();

			if ($memberspuse) {
				$existitem = SocietyMember::with('society')->whereRaw('partner_id=? and year=?', [$memberspuse->id, trim($request->input('txtYear'))])->first();

				// verificar si el esposo(a) es miembro de una organización en el año seleccionado
				if ($existitem) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.members', $item->id), AppHelper::ERROR, ["No se puede agregar el socio por que su esposo(a) ya es miembro de la organización {$existitem->society->social_razon} en el año seleccionado."]);
				}
			}

			// verificar si el socio tiene esposo(a)
			if ($partner->spouse) {
				$spouse = Partner::whereRaw('dni=?', [$partner->spouse->dni])->first();

				// verificar si el esposo(a) es miembro
				if($spouse) {
					$existmember = SocietyMember::whereRaw('partner_id=? and year=?', [$spouse->id, trim($request->input('txtYear'))])->first();

					// verificar si el esposo(a) es miembro de una organización en el año seleccionado
					if ($existmember) {
						DB::rollBack();

						return AppHelper::redirect(route('societies.members', $item->id), AppHelper::ERROR, ["No se puede agregar el socio por que su esposo(a) ya es miembro de la organización {$existitem->society->social_razon} en el año seleccionado."]);
					}
				}
			}

			$member = new SocietyMember();
			$member->id = uniqid();
			$member->society_id = $item->id;
			$member->partner_id = $partner->id;
			$member->year = trim($request->input('txtYear'));
			$member->assets = null;

			$member->save();

			DB::commit();

			return AppHelper::redirect(route('societies.members', $id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
		} catch (\Exception $e) {
			DB::rollBack();

			return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.members', $id));
		}
	}

	public function deleteMember($id) {
		$item = SocietyMember::find($id);

		if (!$item) {
			return AppHelper::redirect(route('societies.members', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		try {
			DB::beginTransaction();

			$item->delete();

			DB::commit();

			return AppHelper::redirect(route('societies.members', $item->society_id), AppHelper::SUCCESS, ['Operación realizó con éxito.']);
		} catch (\Exception $e) {
			DB::rollBack();

			return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.members', $item->society_id));
		}
	}

	public function getProjects(Request $request, $id) {
		$item = Society::find($id);
		$year = $request->input('year') ? $request->input('year') : date('Y');
		$search = $request->input('search') ? $request->input('search') : '';

		if (!$item) {
			return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		$projects = $item->SocietyProjects()->with('project')->whereRaw('year=?', [$year])->whereHas('project', function($query) use ($search) {
			$query->whereRaw('concat(name, category) like ?', ['%' . $search . '%']);
		})->paginate(10);

		$projects->appends(['year' => $year]);
		$projects->appends(['search' => $search]);

		$projects->onEachSide(0);

		$allprojects = Project::all();

		return view('societies.projects', ['society' => $item,'allprojects'=>$allprojects, 'projects' => $projects, 'year' => $year, 'years' => range(date('Y'), 2018, -1), 'search' => $search]);
	}

	public function addProject(Request $request, $id) {
		if ($request->isMethod('post')) {
			try {
				DB::beginTransaction();

				$item = Society::find($id);

				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.index'), AppHelper::ERROR, ['Registro no encontrado.']);
				}

				$errors = AppHelper::validate(
					[
						'project' => trim($request->input('txtProjectName')),
						'year' => trim($request->input('txtYear'))
					],
					[
						'project' => ['required', 'string', 'exists:projects,id'],
						'year' => ['required', 'numeric']
					]
				);

				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.projects', $item->id), AppHelper::ERROR, $errors);
				}

				$existsociety = SocietyProject::whereRaw('society_id=? and year=?', [$item->id, trim($request->input('txtYear'))])->first();

				if ($existsociety) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.projects', $item->id), AppHelper::ERROR, ["La organización ya tiene registrado el proyecto {$existsociety->project->name} en el año seleccionado."]);
				}

				$project = Project::find(trim($request->input('txtProjectName')));

				$societyproject = new SocietyProject();
				$societyproject->id = uniqid();
				$societyproject->society_id = $item->id;
				$societyproject->project_id = $project->id;
				$societyproject->year = trim($request->input('txtYear'));
				$societyproject->liquidation = 0;

				$societyproject->save();

				DB::commit();

				return AppHelper::redirect(route('societies.projects', $id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.projects', $id));
			}
		}
	}

	public function editProjectAssets(Request $request, $id) {
		if ($request->isMethod('put')) {
			try {
				DB::beginTransaction();
				$item = SocietyProject::find($id);

				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
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

					return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, $errors);
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
							'status' => $asset['status'] 
						];
					}
				}

				$item->assets = $newAssets;

				$item->save();

				DB::commit();

				return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.projects', $item->society_id));
			}
		}

		$item = SocietyProject::find($id);

		if (!$item) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		return view('societies.edit-project-assets', ['project' => $item]);
	}

	public function editProjectFiles(Request $request, $id) {
		if ($request->isMethod('put')) {
			try {
				DB::beginTransaction();

				$item = SocietyProject::find($id);

				if (!$item) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
				}

				// validar arhciivo solo pdf, maximo 20MB
				$errors = AppHelper::validate(
					[
						'file' => $request->fileUploadFile
					],
					[
						'file' => ['required', 'file', 'mimes:pdf', 'max:20480']
					]
				);


				if (count($errors) > 0) {
					DB::rollBack();

					return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, $errors);
				}

				/* dd($request->file('fileUploadFile')); */

				$file = $request->file('fileUploadFile');

				$filename = uniqid();
				// store on disk local
				Storage::disk('local')->put($filename.'.'.strtolower($file->getClientOriginalExtension()), file_get_contents($file));

				$item->files = $item->files ? [
					...$item->files,
					[
						'originalname' => $file->getClientOriginalName(),
						'filename' => $filename,
						'created_at' => date('Y-m-d H:i:s')
					]
				]: [
					[
						'originalname' => $file->getClientOriginalName(),
						'filename' => $filename,
						'created_at' => date('Y-m-d H:i:s')
					]
				];

				$item->save();

				DB::commit();

				return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
			} catch (\Exception $e) {
				DB::rollBack();

				return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.projects', $item->society_id));
			}
		}

		$item = SocietyProject::find($id);

		if (!$item) {
			return '<div class="alert alert-danger">Registro no encontrado.</div>';
		}

		return view('societies.edit-project-files', ['project' => $item]);
	}

	public function downloadProjectFile($id, $filename) {
		$item = SocietyProject::find($id);

		if (!$item) {
			return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		$file = collect($item->files)->where('filename', $filename)->first();

		if (!$file) {
			return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, ['Archivo no encontrado.']);
		}

		$filepath = storage_path('app/private/'.$filename.'.'.pathinfo($file['originalname'], PATHINFO_EXTENSION));

		return response()->download($filepath, $file['originalname']);
	}

	public function deleteProject($id) {
		$item = SocietyProject::find($id);

		if (!$item) {
			return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::ERROR, ['Registro no encontrado.']);
		}

		try {
			DB::beginTransaction();

			$item->delete();

			DB::commit();

			return AppHelper::redirect(route('societies.projects', $item->society_id), AppHelper::SUCCESS, ['Operación realizada con éxito.']);
		} catch (\Exception $e) {
			DB::rollBack();

			return AppHelper::redirectException(__CLASS__, __FUNCTION__, $e->getMessage(), route('societies.projects', $item->society_id));
		}
	}
}