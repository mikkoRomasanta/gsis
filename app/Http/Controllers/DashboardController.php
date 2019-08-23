<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Issuance;
use App\Receiving;
use App\Item;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issToday = Issuance::whereDate('created_at', Carbon::today())->get(); // get records created today
        $issWeek = Issuance::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get(); //get records created this week
        $issMonth = Issuance::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get(); //get records created this month

        $recToday = Receiving::whereDate('created_at', Carbon::today())->get(); // get records created today
        $recWeek = Receiving::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get(); //get records created this week
        $recMonth = Receiving::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get(); //get records created this month

        $stocksLow = $this->getStocksLow();

        $data = [
            'issToday' => $issToday,
            'issWeek' => $issWeek,
            'issMonth' => $issMonth,
            'recToday' => $recToday,
            'recWeek' => $recWeek,
            'recMonth' => $recMonth,
            'stocksLow' => $stocksLow
        ];

        return view('dashboard')->with($data);
    }

    public function getStocksLow(){

        // $stocksLow = Item::with('itemStats')->whereRaw('buffer_stocks >= quantity')->where('status', '=', 'ACTIVE')->orderBy('item_name', 'ASC')->paginate(9);
        // do not use Where(use whereRaw) as it will compare the string to the other string and not the column
        $stocksLow = Item::with(['itemStats' => function ($q) {
            $firstDayOfPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
            $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $q->whereBetween('created_at', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth]);}])
            ->whereRaw('buffer_stocks >= quantity')->where('status', '=', 'ACTIVE')->orderBy('item_name', 'ASC')->paginate(9);
            // return dd($stocksLow);
            foreach($stocksLow as $item){
                if(count($item->itemStats) > 0){ // check if item's relation(item_stats) is empty or not
                    foreach($item->itemStats as $stat){
                        if(is_null($stat->ave_issuance)){
                            $dateCreated = new Carbon($item->created_at);
                            $depletionRate = $this->calcDailyIssuance($stat, $dateCreated);
                            if($item->quantity == 0){
                                $item->item_desc = 'Stocks depleted';
                            }
                            else if($depletionRate == 0){
                                $item->item_desc = 'N/A';
                            }
                            else{
                                $item->item_desc = number_format((float)$item->quantity / $depletionRate,2,'.','');
                            }
                        }
                        else if($item->quantity > 0){ // to avoid division by 0
                            $item->item_desc = number_format((float)$item->quantity / $stat->ave_issuance,2,'.','');  // format number to 2 decimal places
                        }
                        else{
                            $item->item_desc = 'Stocks depleted';
                        }
                    } //quantity left / depletion rate = days before depletion. (based on last month's ave_issuance(item_stats) / calcDailyIssuance())
                }
                else if($item->quantity == 0){
                    $item->item_desc = 'Stocks depleted';
                }
                else{$item->item_desc = 'N/A';} //if no record in item_stats / no previous ave_issuance
        }
        return $stocksLow;
    }

    //new items(item_stats = 0/NULL) with 5 or more issuances(this month) will have its depletion rate calculated.
    public function calcDailyIssuance($stat, $dateCreated){
            $issuanceThisMonth = Issuance::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('item_id', $stat->item_id)->get();
            $issuanceCount = count($issuanceThisMonth);
            $days = $this->getDaysSinceCreation($dateCreated);

            if($issuanceCount >= 5){ // minimum issuance to calculate depletion rate
                for($i=0; $i < $issuanceCount; $i++){
                    $qtyArr[$i] = $issuanceThisMonth[$i]['quantity'];
                }
                $qtySum = array_sum($qtyArr);
                $depletionRate = number_format((float)$qtySum / $days,2,'.','');
            }
            else{
                $depletionRate = 0;
            }

        return $depletionRate;
    }

    //day of creation of item - day today
    public function getDaysSinceCreation($dateCreated){
        //now
        $dateNow = Carbon::now();
        $dateNow = explode('-', $dateNow);
        $dayNow = explode(' ', $dateNow[2]);
        $dayNow = $dayNow[0];

        //creation day
        $dateCreated = explode('-', $dateCreated);
        $dayCreated = explode(' ', $dateCreated[2]);
        $dayCreated = $dayCreated[0];

        $daysSince = number_format($dayNow - $dayCreated) + 1; // + date of exact creation

        return $daysSince;
    }

    public function getStocksLowCount(){
        $stocksLow = Item::with('itemStats')->whereRaw('buffer_stocks >= quantity')->where('status', '=', 'ACTIVE')->get();
        $stocksLowCount = count($stocksLow);

        return $stocksLowCount;
    }
}
