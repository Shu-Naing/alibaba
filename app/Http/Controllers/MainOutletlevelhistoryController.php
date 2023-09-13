<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletlevelHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MainOutletLevelHistoryExport;

class MainOutletlevelhistoryController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
              ['name' => 'Main Outlet Level History']
        ];

        $m = date('n');
        $y = date('Y');

        $date = session()->get(MAIN_OUTLET_LEVEL_HISTORY_DATE_FILTER);        

        if($date){
            $m = date('n',strtotime($date));
            $y = date('Y',strtotime($date));
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->whereMonth('date',$m)->whereYear('date',$y)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->whereMonth('date',$m)->whereYear('date',$y)->get();
        }
       

        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];

         
        return view("main-outletlevelhistory.index", compact('breadcrumbs', 'histories','types'));
    }

    public function export(){

        $m = date('n');
        $y = date('Y');

        $date = session()->get(MAIN_OUTLET_LEVEL_HISTORY_DATE_FILTER);        

        if($date){
            $m = date('n',strtotime($date));
            $y = date('Y',strtotime($date));
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->whereMonth('date',$m)->whereYear('date',$y)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->whereMonth('date',$m)->whereYear('date',$y)->get();
        }
       

        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];


        $outlets = getOutlets();
    
        return Excel::download(new MainOutletLevelHistoryExport($histories,$outlets,$types), 'main-outlet-level-history.xlsx');
    }
}
