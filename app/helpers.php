<?php 

use App\Models\OutletItem;
use App\Models\MachineVariant;

    use App\Models\Outlets;
    use App\Models\Machines;
    use App\Models\distributes;
    use App\Models\Counter;    

    define('DS_PENDING', '1');
    define('DS_APPROVE', '2');
    define('MAIN_INV_ID', '1');
    define('OD_COUNTER', '1');
    define('OD_MACHINE', '2');
    define('IS_CUSTOMER', '1');
    define('IS_STORE', '2');

    function getOutlets(){
        
        $Outlets = Outlets::get();

        $Outlets_arr = array();

        foreach($Outlets as $row){
            $Outlets_arr[$row->id] = $row->name;
        }
        return $Outlets_arr;
    }

   

    // function getMachines(){
        
    //     $Machines = Machines::get();

    //     $Machines_arr = array();

    //     foreach($Machines as $row){
    //         $Machines_arr[$row->id] = $row->name;
    //     }
    //     return $Machines_arr;
    // }

    function getMachinesWithOutletID($id){
        $machine_arr = [];
        $counter_arr = [];
        $response = [];
        $counter = Counter::where('outlet_id', $id)->first();
        $counter_arr[$counter->id] = $counter->name;
        
        $machines = Machines::where('outlet_id', $id)->get();
        foreach ($machines as $row) {
            $machine_arr[$row->id] = $row->name;
        }

        $response['counter'] = $counter_arr;
        $response['machine'] = $machine_arr;

        return $response;
    }

    // function getMachinesWithOutletID($id){
    //     $machine_arr = [];
    //     $counter_arr = [];
    //     $response = [];
    //     $counter = Counter::where('outlet_id', $id)->first();
    //     $counter_arr[$counter->id] = $counter->name;
        
    //     $machines = Machines::where('outlet_id', $id)->get();
    //     foreach ($machines as $row) {
    //         $machine_arr[$row->id] = $row->name;
    //     }

    //     $response['counter'] = $counter_arr;
    //     $response['machine'] = $machine_arr;

    //     return $response;
    // }

    // function getMachinesIDWithOutletID($id){
    //     $machine_arr = [];
    //     $counter = Counter::where('outlet_id', $id)->first();
    //     $machine_arr[$counter->id] = $counter->name;
        
    //     $machines = Machines::where('outlet_id', $id)->get();
    //     foreach ($machines as $row) {
    //         $machine_arr[$row->id] = $row->name;
    //     }

    //     return $machine_arr;
    // }

    if(!function_exists('outlet_stock')){
        function outlet_stock($variation_id,$outlet_id = 1){
            
           $outet_item_stock = OutletItem::where('variation_id',$variation_id)->where('outlet_id',$outlet_id)->value('quantity');
            return $outet_item_stock;
        }
    }

    if(!function_exists('machine_stock')){
        function machine_stock($variation_id,$machine_id){
            
           $machine_item_stock = MachineVariant::where('variant_id',$variation_id)->where('machine_id',$machine_id)->value('quantity');
            return $machine_item_stock;
        }
    }

    if(!function_exists('total_store_stock')){
        function total_store_stock($variation_id){
           $total_store_stock = OutletItem::where('variation_id',$variation_id)->where('outlet_id','!=',1)->sum('quantity');
            return $total_store_stock;
        }
    }

    if(!function_exists('total_machine_stock')){
        function total_machine_stock($variation_id){
           $total_store_stock = MachineVariant::where('variant_id',$variation_id)->sum('quantity');
            return $total_store_stock;
        }
    }


    // function outlet_variation_total_price($variation_id){
    //     $total = OutletItem::where('variation_id',$variation_id)->sum('')
    // }

    // if(!function_exists('grand_total_price')){
    //     function grand_total_price($variation_id){
    //        $grand_total_price = MachineVariant::with('variants')->get();
    //         return $grand_total_price;
    //     }
    // }


?>
