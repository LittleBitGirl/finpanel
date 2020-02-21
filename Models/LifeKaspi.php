<?php

namespace App\Models\Life;

use Illuminate\Database\Eloquent\Model;


class LifeKaspi extends Model{

//    protected $connection = 'main';
    protected $table = 'lifeKaspi';

    public function scopeRange($query, $range)
    {
        return $query->whereBetween('lifeDate', $range)
            ->orWhereBetween('sysDate', $range);
    }
}
