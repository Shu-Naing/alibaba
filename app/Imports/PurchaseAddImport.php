<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Variation;
use App\Models\OutletItem;
use Illuminate\Support\Str;
use App\Models\OutletItemData;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchasedPriceHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PurchaseAddImport implements ToModel,WithHeadingRow
{
   
    // private $brands,$categories,$units,$product;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        // return $row;
        // $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['received_date']);
        $variation_id = Variation::where('item_code', $row['item_code'])->value('id');

        $outlet_item_id = OutletItem::where('outlet_id', MAIN_INV_ID)->where('variation_id',$variation_id)->value('id');
        // return $outlet_item_id;

        if ($outlet_item_id) {
            OutletItemData::create([
                'outlet_item_id' => $outlet_item_id,
                'points' => $row['point'],
                'tickets' => $row['ticket'],
                'kyat' => $row['kyat'],
                'purchased_price' => $row['purchased_price'],
                'quantity' => $row['qty'],
                'grn_no' => $row['grn_no'],
                'received_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['received_date']),
                'country' => array_search($row['country'],config('constants.countries')),
                'created_by' => Auth::user()->id,
            ]);

            PurchasedPriceHistory::create([
                'variation_id' => $variation_id,
                'purchased_price' => $row['purchased_price'],
                'points' => $row['point'],
                'tickets' => $row['ticket'],
                'kyat' => $row['kyat'],
                'quantity' => $row['qty'],
                'grn_no' => $row['grn_no'],
                'received_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['received_date']),
                'created_by' => Auth::user()->id,
            ]);
        }
    }

    
}
