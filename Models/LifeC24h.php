<?php

namespace App\Models\Life;

use Illuminate\Database\Eloquent\Model;

class LifeC24h extends Model {

//    protected $connection = 'main';
	protected $table = 'lifeC24h';

	public function scopeRange($query, $range)
	{
		return $query->whereBetween('lifeDate', $range)
			->orWhereBetween('sysDate', $range);
	}
}

