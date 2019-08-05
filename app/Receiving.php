<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{

    public function item(){
        return $this->belongsTo('App\Item');
    }
}
