<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $table = 'project_members';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function member()
	{
		return $this->belongsTo(Partner::class, 'partner_id', 'id');
	}

	public function project()
	{
		return $this->belongsTo(Project::class, 'project_id', 'id');
	}
}
