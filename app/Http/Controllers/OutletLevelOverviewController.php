<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Outlets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OutletLevelOverview;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletLevelOverviewExport;
use App\Imports\OutletleveloverviewsImport;
use App\Exports\OutletleveloverviewSampleExport;

class OutletLevelOverviewController extends Controller

{
    public function index() {
        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;
        $from_date = session()->get(OUTLET_LEVEL_OVERVIEW_FROM_DATE_FILTER);
        $to_date = session()->get(OUTLET_LEVEL_OVERVIEW_TO_DATE_FILTER);
      
        $outletleveloverview = DB::table('outlet_level_overviews as oso')
        ->select(
            'oso.*',
            'oid.points','oid.tickets','oid.kyat','oid.purchased_price',
            'outlets.name',
            'products.unit_id','products.category_id',
            'variations.image','variations.size_variant_value'
        )
        ->join('outlets','outlets.id','oso.outlet_id')
        ->join('variations', 'variations.item_code', '=', 'oso.item_code')
        ->join('products','products.id','variations.product_id')
        ->join('outlet_items as oi', 'oi.variation_id', '=', 'variations.id')
        ->join(DB::raw('(SELECT oid1.*
                        FROM outlet_item_data oid1
                        WHERE oid1.id IN (SELECT MAX(oid2.id)
                                        FROM outlet_item_data oid2
                                        GROUP BY oid2.outlet_item_id)) as oid'), function ($join) {
            $join->on('oid.outlet_item_id', '=', 'oi.id');
        })
        ->whereNotNull('oso.item_code')
        ->where('oso.outlet_id', '!=', BODID)
        ->where('oso.outlet_id', '!=', DEPID)
        ->groupBy('oso.item_code', DB::raw('MONTH(oso.date)'), DB::raw('YEAR(oso.date)'), 'oso.outlet_id')
        ->orderBy('oso.date', 'DESC');
      
        if(session()->get(OUTLET_LEVEL_OVERVIEW_FILTER)){
            $outletleveloverview = $outletleveloverview->where('oso.outlet_id',session()->get(OUTLET_LEVEL_OVERVIEW_FILTER));
        }
        if($login_user_role == 'Outlet'){
            $outletleveloverview = $outletleveloverview->where('oso.outlet_id',$login_user_outlet_id);
        }
        if($from_date){
            $outletleveloverview = $outletleveloverview->where('oso.date', '>=', $from_date);
        }
        if($to_date){
            $outletleveloverview = $outletleveloverview->where('oso.date', '<=', $to_date);
        }
        
        $outletleveloverview = $outletleveloverview->get();
        
        if($login_user_role == 'Outlet'){
            $data = Outlets::where('id',$login_user_outlet_id)->get();
            $outlets = [];
            if($data){
                foreach($data as $outlet){
                    $outlets[$outlet->id] = $outlet->name;
                }
            }
        }else{
            $outlets = getOutlets(true);
        }

        $categories = getCategories();
        $units = getUnits();
        $size_variants = getSizeVariants();
        
        // return $outlets;
        return view("outletleveloverview.index", compact('outletleveloverview','outlets','categories','units','size_variants'));

    }

    public function create() {
        $outlets = getFromOutlets(true);
        return view("outletleveloverview.create", compact('outlets'));
    }

    public function store(Request $request) {
        $this->validate($request,[ 
            'date' => 'required',            
            'outlet_id' => 'required',
            'item_code' => 'required',
            'opening_qty' => 'required',
        ]);

        $month = date('n', strtotime($request->date));
        $year = date('Y', strtotime($request->date));
        // return $month;
        $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
        ->where('outlet_id', $request->outlet_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('item_code',$request->item_code)->first();

        if($outletleveloverview){     
            $opening_qty = $outletleveloverview->opening_qty + $request->opening_qty;
            $input = [];
            $input['opening_qty'] = $opening_qty;
            $input['balance'] = ($opening_qty + $outletleveloverview->receive_qty) - $outletleveloverview->issued_qty;
            $input['updated_by'] = Auth::user()->id;
            $outletleveloverview->update($input);
        }else {
            $input = [];
            $input['date'] = $request->date;
            $input['outlet_id'] = $request->outlet_id;
            $input['item_code'] = $request->item_code;
            $input['opening_qty'] = $request->opening_qty;
            $input['balance'] = $request->opening_qty;
            $input['created_by'] = Auth::user()->id;
            OutletLevelOverview::create($input);
        }

        return redirect()->back()->with('success', 'Opening Qty is create or update success');
    }

    public function checkoutletleveloverview(Request $request) {
        $outletleveloverview_id = $request->id;
        $check =  $request->check;
        $c = 0;
        if($check == 'true') {
            $c = 1;
        }
        $input = [];
        $input['is_check'] = $c;
        $input['updated_by'] = auth()->user()->id;
        $outletleveloverview = OutletLevelOverview::find($outletleveloverview_id);
        $outletleveloverview->update($input);
        // return $input;
        return "success data";
    }


    public function export(){

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;
        $from_date = session()->get(OUTLET_LEVEL_OVERVIEW_FROM_DATE_FILTER);
        $to_date = session()->get(OUTLET_LEVEL_OVERVIEW_TO_DATE_FILTER);

        $outletleveloverview = DB::table('outlet_level_overviews as oso')
        ->select(
            'oso.*',
            'oid.points','oid.tickets','oid.kyat','oid.purchased_price',
            'outlets.name'
        )
        ->join('outlets','outlets.id','oso.outlet_id')
        ->join('variations', 'variations.item_code', '=', 'oso.item_code')
        ->join('outlet_items as oi', 'oi.variation_id', '=', 'variations.id')
        ->join(DB::raw('(SELECT oid1.*
                        FROM outlet_item_data oid1
                        WHERE oid1.id IN (SELECT MAX(oid2.id)
                                        FROM outlet_item_data oid2
                                        GROUP BY oid2.outlet_item_id)) as oid'), function ($join) {
            $join->on('oid.outlet_item_id', '=', 'oi.id');
        })
        ->whereNotNull('oso.item_code')
        ->where('oso.outlet_id', '!=', BODID)
        ->where('oso.outlet_id', '!=', DEPID)
        ->groupBy('oso.item_code', DB::raw('MONTH(oso.date)'), DB::raw('YEAR(oso.date)'), 'oso.outlet_id')
        ->orderBy('oso.date', 'DESC');
      
        if(session()->get(OUTLET_LEVEL_OVERVIEW_FILTER)){
            $outletleveloverview = $outletleveloverview->where('oso.outlet_id',session()->get(OUTLET_LEVEL_OVERVIEW_FILTER));
        }
        if($login_user_role == 'Outlet'){
            $outletleveloverview = $outletleveloverview->where('oso.outlet_id',$login_user_outlet_id);
        }

        if($from_date){
            $outletleveloverview = $outletleveloverview->where('oso.date', '>=', $from_date);
        }
        if($to_date){
            $outletleveloverview = $outletleveloverview->where('oso.date', '<=', $to_date);
        }
        
        $outletleveloverview = $outletleveloverview->get();

        return Excel::download(new OutletLevelOverviewExport($outletleveloverview), 'outlet-level-overview.xlsx');
      
    }

    public function updateoutletlevelphysicalqty(Request $request) {
        // return $request;
        $physical_qty = $request->physical_qty;
        $balance_qty =  $request->balance_qty;
        $difference_qty =  $balance_qty - $physical_qty;
        

        $input = [];
        $input['physical_qty'] = $physical_qty;
        $input['difference_qty'] = $difference_qty;
        $input['updated_by'] = auth()->user()->id;
        $outletstocksoverview = OutletLevelOverview::find($request->id);
        $outletstocksoverview->update($input);
        return "success data";
    }

    public function exportSampleOutletlevelopeningqty(Request $request) {
        // return "hello";
        return Excel::download(new OutletleveloverviewSampleExport(), 'opening_stock_quantity.xlsx');

    }

    public function importOutletlevelopeningqty(Request $request) {
        // return $request;

        $file = $request->file('file');

        try {
            Excel::import(new OutletleveloverviewsImport, $file);
            return redirect()->back()->with('success', 'Outletlevel Overview imported successfully.');
        }catch (Exeption $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

    }

}
