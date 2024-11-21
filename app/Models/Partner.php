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
}
