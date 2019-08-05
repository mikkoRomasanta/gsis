<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsExport;
use App\Imports\IssuancesImport;

class ExcelController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('excel.excel');
    }

    public function exportItems(){
        $dateNow = date('d-m-y');

        return Excel::download(new ItemsExport, 'items_'.$dateNow.'.xlsx');
    }

    public function importIssuances(){
        if(is_null(request()->file('file'))){
            return redirect()->back()->with('error', 'No file selected!');
        }
        else{
            Excel::import(new IssuancesImport,request()->file('file'));
            return back();
        }

    }

}
