<?php

namespace App\Models\Life;

use Illuminate\Database\Eloquent\Model;


class LifePost extends Model{

//    protected $connection = 'main';
    protected $table = 'lifePost';

    public function scopeRange($query, $range)
    {
        return $query->whereBetween('lifeDate', $range)
            ->orWhereBetween('sysDate', $range);
    }
}
