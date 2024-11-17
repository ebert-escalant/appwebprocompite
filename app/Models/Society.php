<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
	protected $table = 'societies';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function societyMembers()
	{
		return $this->hasMany(SocietyMember::class, 'society_id', 'id');
	}

	public function societyProjects()
	{
		return $this->hasMany(SocietyProject::class, 'society_id', 'id');
	}

	public function partners()
	{
		return $this->belongsToMany(Partner::class, 'society_members', 'society_id', 'partner_id', 'id', 'id');
	}

	public function projects()
	{
		return $this->belongsToMany(Project::class, 'society_projects', 'society_id', 'project_id', 'id', 'id');
	}
}
