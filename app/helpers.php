<?php 

    use App\Models\Outlets;
    use App\Models\distributes;
    define('DS_PENDING', '1');
    define('DS_APPROVE', '2');
    

    function getOutlets(){
        
        $Outlets = Outlets::get();

        $Outlets_arr = array();

        foreach($Outlets as $row){
            $Outlets_arr[$row->id] = $row->name;
        }
        return $Outlets_arr;
    }

?>
