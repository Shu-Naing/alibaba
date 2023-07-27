<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use Illuminate\Http\Request;
use App\Models\OutletlevelHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletLevelHistoryExport;

class OutletlevelhistoryController extends Controller
{
    public function index() {
        $breadcrumbs = [
              ['name' => 'Outlet Level History']
        ];

        $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);

        if($outlet_id){
            $histories = OutletlevelHistory::where('outlet_id',$outlet_id)->get();
        }else{
            $histories = OutletlevelHistory::all();
        }
        
        // return $histories;
        $outlets = getOutlets();
        $all_outlets = Outlets::all();
        // return $all_outlets;
        // return $outlets[1];
        return view("outletlevelhistory.index", compact('breadcrumbs', 'histories', 'outlets','all_outlets'));
    }

    public function export(){
        $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);

        if($outlet_id){
            $histories = OutletlevelHistory::where('outlet_id',$outlet_id)->get();
        }else{
            $histories = OutletlevelHistory::all();
        }
        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];
        // return $histories;
        $outlets = getOutlets();
        return Excel::download(new OutletLevelHistoryExport($histories,$outlets,$types), 'outlet-level-history.xlsx');
    }

    public function checkoutletlevelhistory(Request $request) {
        // return "hello";
        $outletlevelhistory_id = $request->id;
        $check =  $request->check;
        $c = 0;
        if($check == 'true') {
            $c = 1;
        }
        $input = [];
        $input['is_check'] = $c;
        $input['updated_by'] = auth()->user()->id;
        $outletlevelhistory = OutletlevelHistory::find($outletlevelhistory_id);
        $outletlevelhistory->update($input);
        // return $input;
        return "success data";
    }
}
