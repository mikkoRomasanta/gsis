<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Receiving;
use App\Item;
use Validator;

class ReceivingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $s = $request->input('s');

        return view('receivings.index')->with('search',$s);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'numeric|min:1'
        ]);

        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');
        $received_by = $request->input('received_by');

        $rec = new Receiving;
        $rec->item_id = $item_id;
        $rec->quantity = $quantity;
        $rec->received_by = $received_by;
        $rec->save();

        $quantityCount = Item::where('id', $item_id)->value('quantity');
        $newQuantity = $quantity + $quantityCount;
        Item::where('id', $item_id)->update(array('quantity' => $newQuantity)); // Add quantity to items

        // return redirect('/items')->with('success', 'Stocks received.');
        return redirect()->back()->with('success', 'Stock received');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function getAll(){
        $receivingDt = Receiving::select('receivings.id', 'receivings.quantity', 'receivings.received_by', 'receivings.created_at', 'items.item_name', 'items.uom')
        ->leftJoin('items', 'receivings.item_id', '=', 'items.id')
        ->get();

        return $receivingDt->toJson();
    }

    public function storeMulti(Request $request){
        $rules = [];


        foreach($request->input('quantity') as $key => $value) {
            $rules["quantity.{$key}"] = 'numeric|min:1';
        }


        $validator = Validator::make($request->all(), $rules);


        if ($validator->passes()) {

            $item_id = $request->item_id;
            $quantity = $request->quantity;
            $received_by = $request->received_by;

            for($count = 0; $count < count($item_id); $count++){

                $rec = new Receiving;
                $rec->item_id = $item_id[$count];
                $rec->quantity = $quantity[$count];
                $rec->received_by = $received_by[$count];
                $rec->save();

                $quantityCount = Item::where('id', $item_id[$count])->value('quantity');
                $newQuantity = $quantity[$count] + $quantityCount;
                Item::where('id', $item_id[$count])->update(array('quantity' => $newQuantity));
            }


            return redirect()->back()->with('success', 'Stock received');



            return response()->json(['success'=>'done']);
        }

        return redirect()->back()->with('error', 'Quantity cannot be 0');
        // return response()->json(['error'=>$validator->errors()->all()]);
    }
}
