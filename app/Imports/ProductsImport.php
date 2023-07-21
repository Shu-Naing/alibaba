<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Units;
use App\Models\Brands;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Categories;
use App\Models\OutletItem;
use Illuminate\Support\Str;
use App\Models\OutletItemData;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchasedPriceHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow
{
   
    // private $brands,$categories,$units,$product;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        
        $created_by = Auth::user()->id;
       

        $category_name = $row['category_name'];
        $category = Categories::firstOrCreate(
            ['category_name' => $category_name],
            ['create_by' => $created_by],
        );

        $brand = Brands::firstOrCreate(
            ['brand_name' => $row['brand_name']],
            ['created_by' => $created_by],
        );

        $unit_name = $row['unit_name'];
        
        $unit = Units::firstOrCreate(
            ['name' => $unit_name],
            ['created_by' => $created_by],
        );

        $product = Product::firstOrCreate(
            ['product_name' => $row['product_name']],
            ['sku' => $row['sku'], 
             'brand_id' => $brand->id , 'category_id' => $category->id ,
              'unit_id' => $unit->id ,'company_name' => $row['company_name'],
              'country' => $row['country'], 
              'expired_date' =>  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expired_date']),
              'received_date' =>\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['received_date']),
              'created_by' => $created_by,
              ]
        );

       

        $variation = Variation::firstOrCreate(
            ['item_code'  => $row['item_code']],
            [
            'product_id' => $product->id,
            'select' => $row['select'],
            'value' => $row['value'],
            'alert_qty' => $row['alert_qty'],
            'purchased_price' => $row['purchased_price'],
            'points' => $row['point'],
            'tickets' => $row['ticket'],
            'kyat' => $row['kyat'],
            'barcode'=> $row['barcode'],
            'created_by' => $created_by,
        ]);

     

        $outlet_id = Auth::user()->outlet->id;

        $outlet_item = OutletItem::firstOrCreate([
            'outlet_id' => $outlet_id,'variation_id' => $variation->id,
            'created_by' => $created_by,
        ]);

        $outlet_item_data = OutletItemData::firstOrCreate([
            'outlet_item_id' => $outlet_item->id,
            'purchased_price' => $variation->purchased_price,
                'points' => $variation->points,
                'tickets' => $variation->tickets,
                'kyat' => $variation->kyat,
                'quantity' => $row['received_qty'],
                'created_by' => $created_by,
        ]);

        $purchased_price_history = PurchasedPriceHistory::firstOrCreate(
            ['variation_id' => $variation->id,
            'purchased_price' => $variation->purchased_price,
                'points' => $variation->points,
                'tickets' => $variation->tickets,
                'kyat' => $variation->kyat,
                'quantity' => $row['received_qty'],
                'created_by' => $created_by,
            ]
        );


        return $product;
       
    }

    
}
