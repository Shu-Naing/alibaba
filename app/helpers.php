<?php 

    use App\Models\Outlets;
    use App\Models\Machines;
    use App\Models\distributes;
    use App\Models\Counter;    

    define('DS_PENDING', '1');
    define('DS_APPROVE', '2');
    define('MAIN_INV_ID', '1');
    define('OD_COUNTER', '1');
    define('OD_MACHINE', '2');

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


?>
