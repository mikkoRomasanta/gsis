<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Item;
use App\ItemStat;
use App\Issuance;
use App\Receiving;
use DB;

class UpdateItemStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:itemstats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update previous months stats and create new stats for new month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dateNow = Carbon::now()->format('M-Y');
        $dateSaved = DB::table('item_stats_update')->orderBy('id', 'desc')->take(1)->get(); //get last date on item_stats_update

        foreach($dateSaved as $dateSaved){
            $date = $dateSaved->date;
        }

        if($dateNow != $date){
            $this->saveItemStats($dateNow);
        }
    }

    public function saveItemStats($dateNow){
        $items = Item::with(['itemStats' => function ($q) {$q->orderBy('id', 'desc');}])->where('status', '=', 'ACTIVE')->get();

        foreach($items as $item){
            foreach($item->itemStats as $stat){

                $ave_issuance = $this->calcMonthlyIssuance($stat->item_id);
                $rec_month = $this->recThisMonth($stat->item_id);

                //previous month's item_stat
                $id = $stat->id;
                $statOld = ItemStat::find($id);
                $statOld->end_stocks = $item->quantity;
                if($ave_issuance > 0){
                    $statOld->ave_issuance = $ave_issuance;
                }
                $statOld->rec_stocks = $rec_month;
                $statOld->save();

                //new item_stat
                $stat = new ItemStat;
                $stat->item_id = $item->id;
                $stat->beg_stocks = $item->quantity;
                $stat->save();
                break;
            }
        }

        //set item_stats_update
        $created = date('y-m-d');
        DB::table('item_stats_update')->insert([
            'date' => $dateNow,
            'created_at' => $created
        ]
        );
    }

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

    public function recThisMonth($id){
        $firstDayOfPreviousMonth = Carbon::now()->startOfMonth()->subMonth()->toDateString();
        $lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $receivingLastMonth = Receiving::whereBetween('created_at', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])->where('item_id', $id)->get();
        $receivingLastMonthCount = count($receivingLastMonth);

        if($receivingLastMonthCount > 0){
            for($i=0; $i < $receivingLastMonthCount; $i++){
                $qtyArr[$i] = $receivingLastMonth[$i]['quantity'];
            }
            $qtySum = array_sum($qtyArr);
        }
        else{
            $qtySum = 0;
        }

        return $qtySum;
    }

}
