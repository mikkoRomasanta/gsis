<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Issuance;
use App\Item;
use App\Admin;
use Auth;
use Carbon\Carbon;

class IssuancesImport implements ToCollection, WithValidation, WithHeadingRow
{


    public function collection(Collection $rows){
        foreach($rows as $row){
            $id =$row['item'];
            $qty = $row['quantity'];
            $dateTime = new Carbon($row['datetime']);

            Issuance::create([
                'item_id' => $id,
                'quantity' => $qty,
                'area' => $row['area'],
                'shift' => $row['shift'],
                'issued_by' => $row['issuedby'],
                'received_by' => $row['receivedby'],
                'created_at' => $dateTime
            ]);
            // return dd($row);
            // $id = $row[0];
            // $qty = $row[1];
            // $dateTime = new Carbon($row[6]);

            // Issuance::create([
            //     'item_id' => $id,
            //     'quantity' => $qty,
            //     'area' => $row[2],
            //     'shift' => $row[3],
            //     'issued_by' => $row[4],
            //     'received_by' => $row[5],
            //     'created_at' => $dateTime
            // ]);

            Item::updateQty($id,$qty);
        }

        $rowLength = count($rows);
        //save admin logs
        $user = Auth::user()->username;
        $action1 = 'Imported';
        $action2 = 'Issuances';
        $action3 =  '['.$rowLength.' rows]';
        $remarks = null;
        Admin::insertLog($user, $action1, $action2, $action3, $remarks);
    }

    public function rules(): array{
        return [
            'receivedby' => 'required'
        ];
    }

    public function validationMessages()
    {
        return [
            'receivedby.required' => trans('Received by is required')
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
