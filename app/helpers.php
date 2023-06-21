<?php 

    use App\Models\Outlets;
    use App\Models\Machines;
    use App\Models\distributes;
    use App\Models\Counter;    
    use App\Models\OutletItem; 
    use App\Models\MachineVariant;    

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
        if($counter) {
            $counter_arr[$counter->id] = $counter->name;
        }
        
        $machines = Machines::where('outlet_id', $id)->get();
        if($machines) {
            foreach ($machines as $row) {
                $machine_arr[$row->id] = $row->name;
            }
        }

        $response['counter'] = $counter_arr;
        $response['machine'] = $machine_arr;

        return $response;
    }

    function getIssuedMachinesWithOutletID($id){
        $machine_arr = [];       
        
        $machines = Machines::has('machine_variants')
            ->whereHas('machine_variants', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->where('outlet_id',$id)
            ->get();
        
        if($machines) {
            foreach ($machines as $row) {
                $machine_arr[$row->id] = $row->name;
            }
        }

        return $machine_arr;
    }

    function outlet_stock($variation_id = null,$outlet_id =null){
        $outet_item_stock = OutletItem::where('variation_id',$variation_id)
        ->where('outlet_id',$outlet_id)
        ->value('quantity');

        return $outet_item_stock;
    }

    function getOutletMachineItem($machine_id){
        $item_codes = [];

        $items = MachineVariant::select('variations.id','variations.item_code')
        ->join('variations','variations.id','=','machine_variants.variant_id')
        ->where('machine_variants.machine_id',$machine_id)
        ->get();        

        if($items){
            foreach($items as $row){
                $item_codes[$row->item_code] = $row->item_code;
            } 
        }             

        return $item_codes;
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

    
  

?>
