<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\QuantityCount;
use App\Issuance;
use App\Item;
use DB;
use Validator;

class IssuancesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //datatable
    {
        $s = $request->input('s');

        // $sql = Issuance::select('issuances.id', 'issuances.quantity', 'issuances.issued_by', 'issuances.received_by', 'issuances.created_at', 'areas.area_name', 'items.item_name', 'items.uom')
        // ->leftJoin('areas','issuances.area', '=', 'areas.id')
        // ->leftJoin('items', 'issuances.item_id', '=', 'items.id')
        // ->get();


        return view('issuances.index')->with('search', $s);
        // return view('issuances.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');

        $quantityCount = Item::where('id', $item_id)->value('quantity'); //get quantity count
         $this->validate($request, [
            'quantity' => ['required', new QuantityCount($quantityCount)], //set quantity count to constructer and check rule
            'received_by' => 'required'
         ]);

        //create issuance
        $item_id = $request->input('item_id');
        $quantity = $request->input('quantity');

        $iss = new Issuance;
        $iss->item_id = $item_id;
        $iss->quantity = $quantity;
        $iss->area = $request->input('area');
        $iss->shift = $request->input('shift');
        $iss->issued_by = $request->input('issued_by');
        $iss->received_by = $request->input('received_by');
        $iss->save();


        $newQuantity = $quantityCount - $quantity;
        Item::where('id', $item_id)->update(array('quantity' => $newQuantity)); //decrease quantity of item


        return redirect('/items/'.$item_id)->with('success', 'Item Issued');
    }

    public function storeMulti(Request $request)
    {
        $rules = [];

        $item_id = $request->item_id;

        foreach($request->input('quantity') as $key => $value) {
            $quantityCount = Item::where('id', $item_id[$key])->value('quantity');
            $rules["quantity.{$key}"] = ['min:0.25'];
        }

        foreach($request->input('received_by') as $key => $value){
            $rules["received_by.{$key}"] = 'required';
        }

        $messages = [
            'received_by.*.required' => 'Please input receipient',
            'quantity.*.min' => 'Minimum amount is 0.25'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->passes()) {

            // $item_id = $request->item_id;
            $quantity = $request->quantity;
            $area = $request->area;
            $shift = $request->shift;
            $received_by = $request->received_by;
            $issued_by = $request->issued_by;

            for($count = 0; $count < count($item_id); $count++){

                $quantityCount = Item::where('id', $item_id[$count])->value('quantity');

                if($quantity[$count] > $quantityCount)
                {
                    return redirect()->back()->with('error', 'Quantity is greater than available stocks');
                }

                $iss = new Issuance;
                $iss->item_id = $item_id[$count];
                $iss->quantity = $quantity[$count];
                $iss->area = $area[$count];
                $iss->shift = $shift[$count];
                $iss->received_by = $received_by[$count];
                $iss->issued_by = $issued_by;
                $iss->save();

                // $quantityCount = Item::where('id', $item_id[$count])->value('quantity');
                $newQuantity = $quantityCount - $quantity[$count];
                Item::where('id', $item_id[$count])->update(array('quantity' => $newQuantity));
            }


            return redirect()->back()->with('success', 'Item issued');

        }

        return redirect()->back()->withErrors($validator->errors()->all());
        // return response()->json(['error'=>$validator->errors()->all()]);
    }


    public function show($id)
    {
        // $iss = Issuance::where('id', '3')->get();
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
        $issuanceDt = Issuance::select('issuances.id', 'issuances.quantity', 'issuances.issued_by', 'issuances.received_by', 'issuances.created_at', 'issuances.shift','areas.area_name', 'items.item_name', 'items.uom')
        ->leftJoin('areas','issuances.area', '=', 'areas.id')
        ->leftJoin('items', 'issuances.item_id', '=', 'items.id')
        ->get();

        // $issuanceDt = Issuance::all()->toJson();
        // $issuanceDt = DB::table('issuances')->select('id','created_at')->get();

        // return Datatables::of($issuanceDt)
        // ->addColumn('newQuantity', function($row){
        //     return $row->quantity.' '.$row->uom;})
        //     ->make(true);
        return $issuanceDt->toJson();
    }
}
