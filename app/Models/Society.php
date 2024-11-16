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
}
