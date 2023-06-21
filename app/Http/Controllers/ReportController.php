<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletStockOverview;

class ReportController extends Controller
{
    public function productReport(){
        return view('reports.products');
    }

    public function machineReport() {
        return view('reports.machine');
    }

    public function outletstockoverviewReport() {
        $outletstockoverviews = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')
                                ->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id')
                                ->get();
        return view('reports.outletstockoverview', compact('outletstockoverviews'));
    }
}
