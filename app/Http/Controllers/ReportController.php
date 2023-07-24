<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\OutletStockOverview;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Exports\OutletstockoverviewsExport;

class ReportController extends Controller
{
    public function productReport(){

        $breadcrumbs = [
            ['name' => 'Report Products', 'url' => route('report.products')],
        ];
        $received_date = session()->get(PD_RECEIVED_DATE_FILTER);

        if( $received_date){
            $reports = Variation::whereHas('product',function ($query) use ($received_date){
            $query->where('received_date',$received_date);})
            ->with('product.brand','product.category','product.unit')
            ->with('sizeVariant')->get();

        }else{
            $reports = Variation::with('product.brand','product.category','product.unit')
            ->with('sizeVariant')->get();
        }

        // return $reports;
        // return $reports;
        $outlets = Outlets::with('machines')->where('id','!=',1)->get();
        // return $outlets;
        return view('reports.products',compact("reports","outlets", 'breadcrumbs'));
    }

    public function exportProduct(){

        $received_date = session()->get(PD_RECEIVED_DATE_FILTER);

        if($received_date){
            $reports = Variation::whereHas('product',function ($query) use ($received_date){
            $query->where('received_date',$received_date);})
            ->with('product.brand','product.category','product.unit')
            ->with('sizeVariant')->get();

        }else{
            $reports = Variation::with('product.brand','product.category','product.unit')
            ->with('sizeVariant')->get();
        }
        $outlets = Outlets::with('machines')->where('id','!=',1)->get();
        // $data = Variation::with('product','outlet_item','product.brand','product.category','product.unit')->get();
        return Excel::download(new ProductsExport($reports,$outlets), 'products.xlsx');
    }

    
    public function machineReport() {
        return view('reports.machine');
    }
    
    public function outletstockoverviewReport() {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'Outlet Stock Overview Report']
        ];

        $outletstockoverviews = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')
        ->whereNotNull('item_code')
        ->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id')
        ->get();
        return view('reports.outletstockoverview', compact('outletstockoverviews', 'breadcrumbs'));
    }
    
    public function exportOutletstockoverview() {
        $outletstockoverviews = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')
        ->whereNotNull('item_code')
        ->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id')
        ->get();
        return Excel::download(new OutletstockoverviewsExport($outletstockoverviews), 'outletstockoverview.xlsx');
    }
}
