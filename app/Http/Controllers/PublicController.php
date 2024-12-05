<?php
namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function queries(Request $request)
	{
		return view ('public.queries');
	}

	public function findByDni($dni)
	{
		try {
			$partner = Partner::select('id', 'dni', 'full_name')->with(['projectMembers'=> function($query) {
				$query->select('id', 'partner_id', 'project_id')->with(['project' => function($query) {
					$query->select('id', 'name', 'society_id');
				}, 'project.society:id,social_razon']);
			}])->whereRaw('dni = ?', [trim($dni)])->first();

			if (!$partner) {
				return response()->json(['type' => 'error', 'message' => 'No se encontrarón resultados']);
			}

			return response()->json(['type' => 'success', 'data' => $partner]);
		} catch(\Exception $e) {
			return response()->json(['type' => 'error', 'message' => 'No se encontrarón resultados']);
		}
	}
}
