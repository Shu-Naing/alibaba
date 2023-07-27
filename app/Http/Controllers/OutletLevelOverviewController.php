<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Outlets;
use Illuminate\Http\Request;
use App\Models\OutletlevelOverview;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletLevelOverviewExport;

class OutletLevelOverviewController extends Controller
{
    public function index() {
        $outletleveloverview = OutletlevelOverview::join('outlets', 'outlets.id', '=', 'outlet_level_overviews.outlet_id');
        if(session()->get(OUTLET_LEVEL_OVERVIEW_FILTER)){
            $outletleveloverview = $outletleveloverview->where('outlet_level_overviews.outlet_id',session()->get(OUTLET_LEVEL_OVERVIEW_FILTER));
        }
        $outletleveloverview = $outletleveloverview->get(); 

       
        $outlets = Outlets::all();
        // return $outlets;
        return view("outletleveloverview.index", compact('outletleveloverview','outlets'));
    }

    public function create() {
        $outlets = getOutlets();
        return view("outletleveloverview.create", compact('outlets'));
    }

    public function store(Request $request) {
        $this->validate($request,[ 
            'date' => 'required',            
            'outlet_id' => 'required',
            'item_code' => 'required',
            'opening_qty' => 'required',
        ]);

        $month = date('m', strtotime($request->date));
        $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
        ->where('outlet_id', $request->outlet_id)
        ->whereMonth('date', $month)
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

        $outletleveloverview = OutletlevelOverview::join('outlets', 'outlets.id', '=', 'outlet_level_overviews.outlet_id');
        if(session()->get(OUTLET_LEVEL_OVERVIEW_FILTER)){
            $outletleveloverview = $outletleveloverview->where('outlet_level_overviews.outlet_id',session()->get(OUTLET_LEVEL_OVERVIEW_FILTER));
        }
        $outletleveloverview = $outletleveloverview->get();

        return Excel::download(new OutletLevelOverviewExport($outletleveloverview), 'outlet-level-overview.xlsx');
    }

}
