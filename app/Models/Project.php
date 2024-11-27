<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	protected $table = 'projects';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function societyProjects()
	{
		return $this->hasMany(SocietyProject::class, 'project_id', 'id');
	}

	public function societies()
	{
		return $this->belongsToMany(Society::class, 'society_projects', 'project_id', 'society_id', 'id', 'id');
	}
}
