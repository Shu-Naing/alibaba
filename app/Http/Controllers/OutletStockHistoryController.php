<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletStockHistory;
use App\Exports\OutletstockhistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class OutletStockHistoryController extends Controller
{
    public function index(){
        $breadcrumbs = [
            ['name' => 'Outlets Stock History', 'url' => route('outletstockhistory.index')],
            ['name' => 'Distribute Products']
        ];
        $histories = OutletStockHistory::select('outlet_stock_histories.*','variations.item_code', 'machines.name as machine_name', 'outlets.name as outlet_name', 'units.short_name as unit_name')
                    ->leftjoin('variations', 'variations.id', '=', 'outlet_stock_histories.variant_id')
                    ->leftjoin('products', 'products.id', '=', 'variations.product_id')
                    ->leftjoin('units', 'units.id', '=', 'products.unit_id')
                    ->leftjoin('machines', 'machines.id', '=', 'outlet_stock_histories.machine_id')
                    ->leftjoin('outlets', 'outlets.id', '=', 'outlet_stock_histories.outlet_id')
                    ->get();
        // return $histories;
        return view('outletstockhistory.index',compact('histories','breadcrumbs'));
    }

    public function exportOutletstockhistory() {
        $histories = OutletStockHistory::select('outlet_stock_histories.*','variations.item_code', 'machines.name as machine_name', 'outlets.name as outlet_name', 'units.short_name as unit_name')
                    ->leftjoin('variations', 'variations.id', '=', 'outlet_stock_histories.variant_id')
                    ->leftjoin('products', 'products.id', '=', 'variations.product_id')
                    ->leftjoin('units', 'units.id', '=', 'products.unit_id')
                    ->leftjoin('machines', 'machines.id', '=', 'outlet_stock_histories.machine_id')
                    ->leftjoin('outlets', 'outlets.id', '=', 'outlet_stock_histories.outlet_id')
                    ->get();
        // return $histories;
        return Excel::download(new OutletstockhistoryExport($histories), 'outletstockhistory.xlsx');
    }

    public function checkoutletstockhistory(Request $request) { 
        $outletstockhistory_id = $request->id;
        $check =  $request->check;
        $c = 0;
        if($check == 'true') {
            $c = 1;
        }
        $input = [];
        $input['is_check'] = $c;
        $input['updated_by'] = auth()->user()->id;
        $outletstocksoverview = OutletStockHistory::find($outletstockhistory_id);
        $outletstocksoverview->update($input);
        // return $input;
        return "success data";
    }
}
