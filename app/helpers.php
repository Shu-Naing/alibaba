<?php 

use App\Models\OutletItem;

    use App\Models\Outlets;
    use App\Models\distributes;
    define('DS_PENDING', '1');
    define('DS_APPROVE', '2');
    define('MAIN_INV_ID', '1');

 

    function getOutlets(){
        
        $Outlets = Outlets::get();

        $Outlets_arr = array();

        foreach($Outlets as $row){
            $Outlets_arr[$row->id] = $row->name;
        }
        return $Outlets_arr;
    }

    if(!function_exists('oultet_stock')){
        function oultet_stock($variation_id,$outlet_id){
            
           $outet_item_stock = OutletItem::where('variation_id',$variation_id)->where('outlet_id',$outlet_id)->value('quantity');
            return $outet_item_stock;
        }
    }


?>
