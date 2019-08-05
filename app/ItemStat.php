<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ItemStat extends Model
{
    protected $table ='item_stats';

    public function item(){
        return $this->belongsTo('App\Item', 'item_id', 'id' );
    }

    public static function getStats($id){ // get stats from table
        $itemStat = ItemStat::where('item_id', $id)->orderBy('id','desc')->first(); //get the latest stat
        $month = new Carbon($itemStat->created_at);

            $stat = [
                'beg' => $itemStat->beg_stocks,
                'rec' => $itemStat->rec_stocks,
                'end' => $itemStat->end_stocks,
                'ave' => $itemStat->ave_issuance,
                'month' => $month->format('F')
            ];

        return $stat;
    }

    public static function getLastMonthStats($id){
        $itemStat = ItemStat::where('item_id', $id)->orderBy('id','desc')->skip(1)->first(); //get previous month's stat
        $month = new Carbon($itemStat->created_at);

        $stat = [
            'beg' => $itemStat->beg_stocks,
            'rec' => $itemStat->rec_stocks,
            'end' => $itemStat->end_stocks,
            'ave' => $itemStat->ave_issuance,
            'month' => $month->format('F')
        ];

        return $stat;
    }
}
