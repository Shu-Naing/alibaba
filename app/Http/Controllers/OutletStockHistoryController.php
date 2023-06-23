<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletStockHistory;

class OutletStockHistoryController extends Controller
{
    public function index(){
        $breadcrumbs = [
            ['name' => 'Outlets Stock History', 'url' => route('outletstockhistory.index')],
            ['name' => 'Distribute Products']
        ];
        $histories = OutletStockHistory::select('outlet_stock_histories.*','variations.item_code', 'machines.name as machine_name', 'outlets.name as outlet_name')
                    ->leftjoin('variations', 'variations.id', '=', 'outlet_stock_histories.variant_id')
                    ->leftjoin('machines', 'machines.id', '=', 'outlet_stock_histories.machine_id')
                    ->leftjoin('outlets', 'outlets.id', '=', 'outlet_stock_histories.outlet_id')
                    ->get();
        // return $histories;
        return view('outletstockhistory.index',compact('histories','breadcrumbs'));
    }
}
