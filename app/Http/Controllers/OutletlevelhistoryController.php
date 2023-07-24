<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletlevelHistory;

class OutletlevelhistoryController extends Controller
{
    public function index() {
        $breadcrumbs = [
              ['name' => 'Outlet Level History']
        ];
        $histories = OutletlevelHistory::all();
        // return $histories;
        $outlets = getOutlets();
        // return $outlets[1];
        return view("outletlevelhistory.index", compact('breadcrumbs', 'histories', 'outlets'));
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
