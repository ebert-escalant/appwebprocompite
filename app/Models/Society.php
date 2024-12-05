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

	public function project()
	{
		return $this->hasOne(Project::class, 'id', 'society_id');
	}

	public function representative()
	{
		return $this->belongsTo(Partner::class, 'id_partner', 'id');
	}
}
