<?php

namespace App\Models\Life;

use Illuminate\Database\Eloquent\Model;


class LifeCloud extends Model{

//    protected $connection = 'main';
    protected $table = 'lifeClouds';

    public function scopeRange($query, $range)
    {
        return $query->whereBetween('lifeDate', $range)
            ->orWhereBetween('sysDate', $range);
    }
}
