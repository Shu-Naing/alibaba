<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use Illuminate\Http\Request;
use App\Models\OutletlevelHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletLevelHistoryExport;
use Auth;

class OutletlevelhistoryController extends Controller
{
    public function index(Request $request) {
        $breadcrumbs = [
              ['name' => 'Outlet Level History']
        ];

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;
        $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);        

        if($login_user_role == 'Outlet'){
            $histories = OutletlevelHistory::where('outlet_id',$login_user_outlet_id)->get();
        }
        elseif($outlet_id){
            $histories = OutletlevelHistory::where('outlet_id',$outlet_id)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id','!=',BODID)->where('outlet_id','!=',DEPID)->get();
        }
        
        $outlets = getFromOutlets(true);
        
        return view("outletlevelhistory.index", compact('breadcrumbs', 'histories', 'outlets'));
    }

    public function export(){
        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;
        $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);        

        if($login_user_role == 'Outlet'){
            $histories = OutletlevelHistory::where('outlet_id',$login_user_outlet_id)->get();
        }
        else if($outlet_id){
            $histories = OutletlevelHistory::where('outlet_id',$outlet_id)->get();
        }else{
            $histories = OutletlevelHistory::where('outlet_id','!=',BODID)->where('outlet_id','!=',DEPID)->get();
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
