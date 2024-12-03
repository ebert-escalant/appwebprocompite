<?php
namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Project;
use App\Models\Society;
use App\Models\SocietyMember;
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
			$partner = Partner::select('id', 'dni', 'full_name')->whereRaw('dni = ?', [trim($dni)])->first();

			if (!$partner) {
				return response()->json(['type' => 'error', 'message' => 'No se encontrarón resultados']);
			}

			$societymembers = SocietyMember::select('id', 'society_id', 'year')->with('society:id,social_razon')->where('partner_id', $partner->id)->orderBy('year', 'desc')->get();

			foreach ($societymembers as $item) {
				$item->project = Project::select('id', 'name')->whereRaw('society_id=? and year=?', [$item->society_id, $item->year])->first();
			}

			$partner->societies = $societymembers;

			return response()->json(['type' => 'success', 'data' => $partner]);
		} catch(\Exception $e) {
			return response()->json(['type' => 'error', 'message' => 'No se encontrarón resultados']);
		}
	}
}
