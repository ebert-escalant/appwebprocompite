<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyProject extends Model
{
    protected $table = 'society_projects';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function society()
	{
		return $this->belongsTo(Society::class, 'society_id', 'id');
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'project_id', 'id');
	}

	protected function casts()
	{
		return [
			'assets' => 'array'
		];
	}
}
