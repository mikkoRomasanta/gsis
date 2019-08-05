<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Item;
use App\ItemStat;
use App\Issuance;
use DB;

class PagesController extends Controller
{
    public function index(){

        $dateNow = Carbon::now()->format('M-Y');
        $dateSaved = DB::table('item_stats_update')->orderBy('id', 'desc')->take(1)->get(); //get last date on item_stats_update

        if(!empty($dateSaved)){

            foreach($dateSaved as $dateSaved){
                $date = $dateSaved->date;
            }

            if($dateNow != $date){
                \Artisan::call('update:itemstats');
                $title = 'Item stats updated for '.$dateNow;
            }
            else{
                $title = 'Welcome to GSIS';
            }
        }
        else{
            $title = 'Welcome to GSIS';
        }

        return view('pages.index')->with('title', $title);
    }

    public function saveItemStats(){
        $items = Item::with(['itemStats' => function ($q) {$q->orderBy('id', 'desc');}])->get();

        foreach($items as $item){
            foreach($item->itemStats as $stat){
                //previous month's item_stat
                $id = $stat->id;
                $statOld = ItemStat::find($id);
                $statOld->end_stocks = $item->quantity;
                $statOld->ave_issuance = $this->calcMonthlyIssuance($stat->item_id);
                $statOld->save();

                //new item_stat
                $stat = new ItemStat;
                $stat->item_id = $item->id;
                $stat->beg_stocks = $item->quantity;
                $stat->save();
                break;
            }
        }
    }

    //calculate previous month's average issuance of items
    public function calcMonthlyIssuance($id){
        $firstDayOfPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $issuanceLastMonth = Issuance::whereBetween('created_at', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])->where('item_id', $id)->get();
        $issuanceLastMonthCount = count($issuanceLastMonth);

        if($issuanceLastMonthCount > 0){
            for($i=0; $i < $issuanceLastMonthCount; $i++){
                $qtyArr[$i] = $issuanceLastMonth[$i]['quantity'];
            }
            $qtySum = array_sum($qtyArr);
            $depletionRate = number_format((float)$qtySum / 30 ,2,'.','');
        }
        else{
            $depletionRate = 0;
        }

        return($depletionRate);
    }

    public function error01(){
        return view('pages.error01');
    }

}
