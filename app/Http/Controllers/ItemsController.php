<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Item;
use App\Issuance;
use App\ItemStat;
use App\Receiving;
use App\Admin;
// use Validator;
use Auth;
use DB; //use regular sql instead of database

class ItemsController extends Controller
{

    public function __construct() //unauthenticated users will only be able to access index page of items
    {
        $this->middleware('auth',['except' => ['index']]);
    }

    public function index(Request $request)
    {

       // $items = Item::all();
       // $item = Item::where('item_name', 'nameOfItem')->get(); // get specific item using where
       // $items = DB::select('SELECT * FROM items'); // for use with use DB;
       // $items = Item::orderBy('id', 'desc')->take(1)->get(); // get a specific number of items from database
       // $items = Item::orderBy('id', 'desc')->get(); // without pagination
        $view = $request->input('view');

        if($view == 'dt'){
            return view('items.index2')->with('view',$view);
        }
        else{
            $view = '';
            $s = $request->input('s');

            $items = Item::orderBy('id', 'desc')->where('status', '=', 'ACTIVE')->search($s)->paginate(12);
            $data = [
                'items' => $items,
                's' => $s,
                'view' => $view
            ];
            return view('items.index')->with($data);
        }
    }

    public function create()
    {
        $user = Auth::user();
        if($user->can('update', Item::class)){
            $uom = DB::table('uom')->orderBy('uom','asc')->pluck('uom','uom');
            return view('items.create')->with('uom',$uom);
        }
        else{
            return redirect('/error01');
        }


    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_name.*' => 'required|max:30',
            'item_desc.*' => 'nullable|max:255',
            'buffer_stocks.*' => 'numeric|min:0',
            'lead_time.*' => 'numeric|min:0',
        ]);

        $item_name = $request->item_name;
        $item_desc = $request->item_desc;
        $uom = $request->uom;
        $payment = $request->payment;
        $buffer_stocks = $request->buffer_stocks;
        $lead_time = $request->lead_time;

        for($i=0;$i<count($item_name);$i++){
            $item = new Item;
            $item->item_name = $item_name[$i];
            $item->item_desc = $item_desc[$i];
            $item->uom = $uom[$i];
            $item->payment = $payment[$i];
            $item->buffer_stocks = $buffer_stocks[$i];
            $item->lead_time = $lead_time[$i];
            // $item->image = $fileNameToStore;
            $item->save();

            $stat = new ItemStat;
            $stat->item_id = $item->id;
            $stat->beg_stocks = 0;
            $stat->end_stocks = 0;
            $stat->save();

                 //save admin logs
            $user = Auth::user()->username;
            $action = 'Created item ['.$item->id.'] '.$item->item_name;
            Admin::insertLog($user, $action);
        }

        return redirect('/items')->with('success', 'Item Created');

        //       // Handle File Upload
        //       if($request->hasFile('image')){
        //         // Get filename with ext
        //         $fileNameWithExt = $request->file('image')->getClientOriginalName();
        //         // Get filename
        //         $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        //         // Get ext
        //         $extension = $request->file('image')->getClientOriginalExtension();
        //         // Filename to store
        //         $fileNameToStore = $filename.'_'.time().'.'.$extension;
        //         // Upload Image
        //         $path = $request->file('image')->storeAs('public/images/uploads', $fileNameToStore);
        //     } else{
        //         $fileNameToStore = "noimage.jpg";
        //     }

    }

    public function show($id)
    {
        $item = Item::withCount(['itemStats'])->find($id); //item_stats_count instead of itemStats_count. method separates camelCase with '_'
        if($item->status == 'ACTIVE'){
            $issuances = Issuance::with('item','area')->orderBy('id','desc')->where('item_id', $id)->take(3)->get(); //get issuances with relationship
            $receivings = Receiving::with('item')->orderBy('id','desc')->where('item_id', $id)->take(3)->get();
            if($item->item_stats_count == 1){ //new items only has 1 record in item_stats
                $stats = ItemStat::getStats($id);
            }
            else{
                $stats= ItemStat::getLastMonthStats($id);
            }

            $pay = $item->payment;
            if($item->payment == 0){
                $item->payment = 'Flowlites';
            }
            else{
                $item->payment = 'CPRF';
            }

            $areas = DB::table('areas')->pluck('area_name','id'); //for form::select

            $data = [
                'item' => $item,
                'issuances' => $issuances,
                'receivings' => $receivings,
                'stats' => $stats,
                'areas' => $areas
            ];
            return view('items.show')->with($data);
        }
    }

    public function edit($id)
    {
        $user = Auth::user();
        if($user->can('update', Item::class)){
            $item = Item::find($id);
            // $uom = DB::table('items')->distinct('uom')->pluck('uom','uom');
            $uom = Item::distinct('uom')->orderBy('uom','asc')->pluck('uom','uom');

            $data = [
                'item' => $item,
                'uom' => $uom
            ];

            return view('items.edit')->with($data);
        }
        else{
            return redirect('/error01');
        }


    }

    public function update(Request $request, $id)
    { // form validation
        $user = Auth::user();
        if($user->can('update', Item::class)){
            $this->validate($request, [
                'item_name' => 'required|max:30',
                'item_desc' => 'max:255',
                'uom' => 'required',
                'buffer_stocks' => 'numeric|min:0',
                'lead_time' => 'numeric|min:0',
                'image' => 'image|nullable|max:1999'
            ]);

            // Handle File Upload
            if($request->hasFile('image')){
                // Get filename with ext
                $fileNameWithExt = $request->file('image')->getClientOriginalName();
                // Get filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get ext
                $extension = $request->file('image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                $path = $request->file('image')->storeAs('public/images/uploads', $fileNameToStore);
            }

            //create item
            $item = Item::find($id);
            $item->item_name = $request->input('item_name');
            $item->item_desc = $request->input('item_desc');
            $item->uom = $request->input('uom');
            $item->payment = $request->input('payment');
            $item->buffer_stocks = $request->input('buffer_stocks');
            $item->lead_time = $request->input('lead_time');
            if($request->hasFile('image')){
                if($item->image != 'noimage.jpg'){
                    //delete image
                    Storage::delete('public/images/uploads/'.$item->image);
                }
                $item->image = $fileNameToStore;
            }

            $item->save();

            //save admin logs
            $user = Auth::user()->username;
            $action = 'Edited item ['.$item->id.'] '.$item->item_name;
            Admin::insertLog($user, $action);

            return redirect('/items')->with('success', 'Item Updated');
        }
        else{
            return redirect('/error01');
        }

    }

    public function destroy($id)
    {
        $user = Auth::user();
        if($user->can('delete', Item::class)){
            $item = Item::find($id);

            if($item->image != 'noimage.jpg'){
                // Delete Image
                Storage::delete('public/images/uploads/'.$item->image);
            }

            // $item->delete(); // set to inactive instead
            $item->status = 'INACTIVE';
            $item->save();

            //save admin logs
            $user = Auth::user()->username;
            $action = 'Deleted item ['.$item->id.'] '.$item->item_name;
            Admin::insertLog($user, $action);


            return redirect('/items')->with('success', 'Item Removed');
        }

        else{
            return redirect('/error01');
        }

    }

    public function receive(){
        $items = DB::table('items')->orderBy('item_name', 'asc')->pluck('item_name', 'id');

        return view('items.receive')->with('items', $items);
    }

    public function receiveMulti(){
        $items = DB::table('items')->where('status', '=', 'ACTIVE')->orderBy('item_name', 'asc')->pluck('item_name', 'id');

        return view('items.receivemulti')->with('items', $items);
    }

    public function issueMulti(){
        $items = DB::table('items')->where('status', '=', 'ACTIVE')->orderBy('item_name', 'asc')->pluck('item_name', 'id');
        $areas = DB::table('areas')->pluck('area_name','id');

        $data = [
            'items' => $items,
            'areas' => $areas
        ];

        return view('items.issuemulti')->with($data);
    }

    public function getUom($id){
        $uom = DB::table('items')->where('id', $id)->pluck('uom');

        return Response()
                        ->json([
                            'success' => true,
                            'uom' => $uom]);
    }

    public function getItemQuantity($id){
        $qty = DB::table('items')->where('id', $id)->pluck('quantity');

        return Response()
                        ->json([
                            'success' => true,
                            'quantity' => $qty]);
    }

    public function getAll(){
        $itemsDt = Item::where('status', 'ACTIVE')->get();
        // foreach($items as $key =>$item){
        //     $itemsDt[$key] = [
        //         'id' => '<a class="color-font-link" href="{{route('."items.show',$item->id)}}".">{{$item->item_name}}</a>",
        //         'item_name' => $item->item_name,
        //         'item_desc' => $item->item_desc,
        //         'quantity' => $item->quantity,
        //         'buffer_stocks' => $item->buffer_stocks,
        //         'uom' => $item->uom,
        //         'lead_time' => $item->lead_time,
        //         'payment' => $item->payment,
        //     ];
        // }
        // return $itemsDt;
        return $itemsDt->toJson();
    }

}
