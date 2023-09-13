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

        $date = session()->get(MAIN_OUTLET_LEVEL_HISTORY_DATE_FILTER);        

        if($date){
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->where('date',$date)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->get();
        }

        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];

         
        return view("main-outletlevelhistory.index", compact('breadcrumbs', 'histories','types'));
    }

    public function export(){

        $date = session()->get(MAIN_OUTLET_LEVEL_HISTORY_DATE_FILTER);        

        if($date){
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->where('date',$date)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id',MAIN_INV_ID)->get();
        }

        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];

        $outlets = getOutlets();
    
        return Excel::download(new MainOutletLevelHistoryExport($histories,$outlets,$types), 'main-outlet-level-history.xlsx');
    }
}
