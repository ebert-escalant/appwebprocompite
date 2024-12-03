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

	public function society()
	{
		return $this->belongsTo(Society::class, 'society_id', 'id');
	}

	protected function casts()
	{
		return [
			'assets' => 'array',
			'file' => 'object'
		];
	}
}
