<?php


namespace App\Models\Life;

use Illuminate\Database\Eloquent\Model;

class LifeHome extends Model {

//    protected $connection = 'main';
    protected $table = 'lifeHome';

    public function scopeRange($query, $range)
    {
        return $query->whereBetween('lifeDate', $range)
            ->orWhereBetween('sysDate', $range);
    }
}
