<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MainOutletleveloverviewExport;

class MainOutletLevelOverviewController extends Controller
{
    public function index() {
      
        $main_outletleveloverview = DB::table('outlet_level_overviews as oso')
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
        ->where('oso.outlet_id', MAIN_INV_ID)
        ->groupBy('oso.item_code', DB::raw('MONTH(oso.date)'), DB::raw('YEAR(oso.date)'), 'oso.outlet_id')
        ->orderBy('oso.date', 'DESC');
      
        if(session()->get(MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER)){
            $main_outletleveloverview = $main_outletleveloverview->where('oso.date',session()->get(MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER));
        }
        
        
        $main_outletleveloverview = $main_outletleveloverview->get();
        
        

        
        
        // return $outlets;
        return view("main-outletleveloverview.index", compact('main_outletleveloverview'));

    }

    function export(){
        $main_outletleveloverview = DB::table('outlet_level_overviews as oso')
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
        ->where('oso.outlet_id', MAIN_INV_ID)
        ->groupBy('oso.item_code', DB::raw('MONTH(oso.date)'), DB::raw('YEAR(oso.date)'), 'oso.outlet_id')
        ->orderBy('oso.date', 'DESC');
      
        if(session()->get(MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER)){
            $main_outletleveloverview = $main_outletleveloverview->where('oso.date',session()->get(MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER));
        }
        
        
        $main_outletleveloverview = $main_outletleveloverview->get();
        
        
        return Excel::download(new MainOutletleveloverviewExport($main_outletleveloverview), 'main_inv_outlet_level_overview.xlsx');
    }
}
