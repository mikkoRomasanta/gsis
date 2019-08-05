<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issuance extends Model
{
    protected $fillable = ['item_id','quantity','area', 'shift', 'issued_by', 'received_by', 'created_at'];

    public function item(){
        return $this->belongsTo('App\Item');
    }

    public function area(){
        return $this->belongsTo('App\Area','area','id');
    }
}
