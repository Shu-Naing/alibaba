<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\OutletStockOverview;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function productReport(){

        $reports = Variation::with('product.brand','product.category','product.unit')->get();
        // return $reports;
        $outlets = Outlets::with('machines')->where('id','!=',1)->get();
        // return $outlets;
        return view('reports.products',compact("reports","outlets"));
    }

    public function exportProduct(){
        $reports = Variation::with('product.brand','product.category','product.unit')->get();
        $outlets = Outlets::with('machines')->where('id','!=',1)->get();
        // $data = Variation::with('product','outlet_item','product.brand','product.category','product.unit')->get();
        return Excel::download(new ProductsExport($reports,$outlets), 'products.xlsx');
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
