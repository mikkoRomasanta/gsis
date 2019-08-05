<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use App\Item;
use DB;


class ItemsExport implements FromCollection, WithHeadings
{

    public function headings(): array{
        return[
            ['ID','Item Name','Item Description','Quantity','Buffer Stocks','UOM','Payment','Lead Time','Status','Stats id','created_at','beg_stocks','rec_stocks','end_stocks','ave_issuance'],
            ['','','','','','','0=flowlites | 1=cprf','','day(s)'],
        ];
    }

    public function collection()
    { //try select ex. Item::select()->leftJoin...
        // $items = Item::join('item_stats', 'item_stats.item_id', '=', 'items.id')->where('items.status', '=', 'ACTIVE')->orderBy('items.id', 'ASC')->get();
        $items = Item::select('items.id','items.item_name', 'items.item_desc','items.quantity','items.buffer_stocks','items.uom','items.payment','items.lead_time','items.status',DB::raw('item_stats.id AS stats_id'),'item_stats.created_at','item_stats.beg_stocks','item_stats.rec_stocks','item_stats.end_stocks','item_stats.ave_issuance')
        ->leftJoin('item_stats', function($q){
            $q->on('item_stats.item_id', '=', 'items.id');
        })
        ->where('status', '=', 'ACTIVE')
        ->whereBetween('item_stats.created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->endOfMonth()->subMonth()])
        ->orderBy('items.id', 'asc')
        ->get();
        // return dd($items);
        return $items;
    }
}

// namespace App\Exports;

// use App\Item;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class ItemsExport implements FromCollection, WithHeadings
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function headings(): array{
//         return[
//             ['ID','Item Name','Item Description','Quantity','UOM','Payment','Buffer Stocks','Lead Time','','Status','Date Created','Date Updated'],
//             ['','','','','','0=flowlites | 1=cprf','','day(s)'],
//         ];
//     }

//     public function collection()
//     {
//         return Item::where('status', '=', 'ACTIVE')->get();
//     }
// }
