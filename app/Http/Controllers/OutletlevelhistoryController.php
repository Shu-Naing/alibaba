<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use Illuminate\Http\Request;
use App\Models\OutletlevelHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletLevelHistoryExport;
use Illuminate\Support\Facades\DB;
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
        $from_date = session()->get(OUTLET_LEVEL_HISTORY_FROM_DATE_FILTER);
        $to_date = session()->get(OUTLET_LEVEL_HISTORY_TO_DATE_FILTER);        
       
        $histories = DB::table('outletlevel_histories as oso')
        ->select(
            'oso.*',
            'outlet_item_data.points',
            'outlet_item_data.tickets',
            'outlet_item_data.kyat',
            'outlet_item_data.purchased_price',
            'products.unit_id',
            'products.category_id',
            'variations.image', // Include the 'variations.image' column here
            'variations.size_variant_value'
        )
        ->join('variations', 'variations.item_code', '=', 'oso.item_code')
        ->join('outlet_items as oi', 'oi.variation_id', '=', 'variations.id')
        ->join('products', 'variations.product_id', '=', 'products.id')
        ->join(DB::raw('(SELECT MAX(id) AS max_id, outlet_item_id,points,tickets,kyat,purchased_price FROM outlet_item_data GROUP BY outlet_item_id) as max_oid'), function ($join) {
            $join->on('oi.id', '=', 'max_oid.outlet_item_id');
        })
        ->join('outlet_item_data','outlet_item_data.id','=','max_oid.max_id')
        ->groupBy('oso.id');

        if($from_date){
            $histories =  $histories->where('date', '>=', $from_date);
        }

        if($to_date){
            $histories =  $histories->where('date', '<=', $to_date);
        }

        if($login_user_role == 'Outlet'){
            $histories = $histories->where('oso.outlet_id',$login_user_outlet_id);
        }
        else if($outlet_id){
            $histories = $histories->where('oso.outlet_id',$outlet_id);
        }

        $histories = $histories->where('oso.outlet_id','!=',BODID)->where('oso.outlet_id','!=',DEPID);

        $histories = $histories->get();
        
        $outlets = getFromOutlets(true);
        $size_variants = getSizeVariants();
        $categories = getCategories();
        $units = getUnits();
        
        return view("outletlevelhistory.index", compact('breadcrumbs', 'histories', 'outlets','size_variants','categories','units'));
    }

    public function export(){
        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;
        $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);
        $from_date = session()->get(OUTLET_LEVEL_HISTORY_FROM_DATE_FILTER);
        $to_date = session()->get(OUTLET_LEVEL_HISTORY_TO_DATE_FILTER); 
        
        $histories = OutletlevelHistory::select('outletlevel_histories.*', 'variations.size_variant_value','variations.image','products.unit_id','products.category_id')
        ->join('variations','variations.item_code','outletlevel_histories.item_code')
        ->join('products','products.id','variations.product_id');
          
        if($from_date){
            $histories =  $histories->where('date', '>=', $from_date);
        }

        if($to_date){
            $histories =  $histories->where('date', '<=', $to_date);
        }

        if($login_user_role == 'Outlet'){
            $histories = $histories->where('outlet_id',$login_user_outlet_id);
        }
        elseif($outlet_id){
            $histories = $histories->where('outlet_id',$outlet_id);
        }else{
            $histories = $histories->where('outlet_id','!=',BODID)->where('outlet_id','!=',DEPID);
        }

        $histories = $histories->get();

        $types = [
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        ];
        // return $histories;
        $outlets = getFromOutlets(true);

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
