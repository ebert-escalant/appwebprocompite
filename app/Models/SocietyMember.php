<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocietyMember extends Model
{
    protected $table = 'society_members';
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

	public function society()
	{
		return $this->belongsTo(Society::class, 'society_id', 'id');
	}

	public function member()
	{
		return $this->belongsTo(Partner::class, 'partner_id', 'id');
	}

	protected function casts()
	{
		return [
			'assets' => 'array'
		];
	}
}
