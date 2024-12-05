<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function projectMembers()
	{
		return $this->hasMany(ProjectMember::class, 'partner_id', 'id');
	}

	public function societies()
	{
		return $this->belongsToMany(Society::class, 'society_members', 'partner_id', 'society_id', 'id', 'id');
	}

	protected function casts()
	{
		return [
			'spouse' => 'object'
		];
	}
}