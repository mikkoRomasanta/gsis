<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function scopeSearch($query,$s) {
        return $query->where('item_name', 'like', '%' .$s. '%')
            ->orWhere('item_desc', 'like', '%' .$s. '%');
    }

    public function issuances(){
        return $this->hasMany('App\Issuance');
    }

    public function itemStats(){
        return $this->hasMany('App\ItemStat', 'item_id', 'id');
    }

    public function receivings(){
        return $this->hasMany('App\Receiving');
    }

    public function latestItemStats(){
        return $this->hasOne('App\ItemStat', 'item_id', 'id')->latest();
    }

    public static function updateQty($id, $qty){
        $quantityCount = Item::where('id', $id)->value('quantity');
        $newQuantity = $quantityCount - $qty;
        Item::where('id', $id)->update(array('quantity' => $newQuantity));
    }

}
