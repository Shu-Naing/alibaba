<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Outlets;
use App\Models\Machines;
use App\Models\Variation;
use App\Models\OutletStockOverview;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OutletstockoverviewsImport implements ToModel,WithHeadingRow
{
   
    // private $brands,$categories,$units,$product;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   

        $outlets = Outlets::all();
        $outlet_arr = [];

        if($outlets){
            foreach($outlets as $outlet){
                $outlet_arr[$outlet->id] = $outlet->name;
            }
        }

        $machines = Machines::all();
        $machine_arr = [];

        if($machines){
            foreach($machines as $machine){
                $machine_arr[$machine->id] = $machine->name;
            }
        }

        $vartiations = Variation::all();
        $variation_arr = [];

        if($vartiations){
            foreach($vartiations as $vartiation){
                $variation_arr[$vartiation->item_code] = $vartiation->item_code;
            }
        }

        $created_by = Auth::user()->id;     
        $updated_by = Auth::user()->id;     

        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']);
        $outlet_id = array_search($row['outlet_name'], $outlet_arr);
        $machine_id = array_search($row['machine_name'], $machine_arr);
        $opening_qty = $row['opening_quantity'];
        $item_code = $row['item_code'];

        $inputs = [];
        $inputs['date'] = $date;
        $inputs['outlet_id'] = $outlet_id;
        $inputs['machine_id'] = $machine_id;
        $inputs['opening_qty'] = $opening_qty;

        if(isset($variation_arr[$item_code]) && isset($outlet_arr[$outlet_id]) && isset($machine_arr[$machine_id])){
            $outletstockoverview = OutletStockOverview::where('date', '=', $date)
            ->where('outlet_id', '=', $outlet_id)
            ->where('machine_id', '=', $machine_id)
            ->where('item_code', '=', $item_code)
            ->first();

            if($outletstockoverview) {
                $receive = $outletstockoverview->receive_qty;
                $issued = $outletstockoverview->issued_qty;
                $physical = $outletstockoverview->physical_qty;
                $balance = ($opening_qty + $receive) - $issued;
                $difference = $balance - $physical;

                $inputs['balance'] = $balance;
                $inputs['difference_qty'] = $difference;
                $inputs['updated_by'] = $updated_by;
                $data = $outletstockoverview->update($inputs);
                // return $data;

            }else {
                $balance = $opening_qty;
                $difference = $balance;

                $inputs['balance'] = $balance;
                $inputs['difference_qty'] = $difference;
                $inputs['item_code'] = $item_code;
                $inputs['created_by'] = $created_by;
                $data = OutletStockOverview::create($inputs);
                // return $data;
            } 
        }      
    }

    
}
