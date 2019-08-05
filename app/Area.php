<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    // protected $table = 'area';

    public function Issuances(){
        return $this->hasMany('App\Issuances','area','id');
    }
}
