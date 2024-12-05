<?php
namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Project;
use App\Models\Society;
use Illuminate\Http\Request;

class AdminController extends Controller
{
	public function dashboard(Request $request)
	{
		$year = $request->input('year') ? $request->input('year') : date('Y');

		$projects = Project::with('society')->withCount(['projectMembers' => function ($query) use ($year) {
			$query->where('year', $year);
		}])->whereHas('projectMembers', function ($query) use ($year) {
			$query->where('year', $year);
		})->get();

		$quantities = [
			'partners' => Partner::count(),
			'projects' => Project::count(),
			'societies' => Society::count()
		];

		return view('dashboard', ['projects' => $projects, 'year' => $year, 'quantities' => $quantities, 'years' => range(date('Y'), 2018, -1)]);
	}
}