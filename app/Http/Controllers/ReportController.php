<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use App\Models\Variation;
use App\Models\distributes;
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

        $outlet_id = session()->get(OUTLET_STOCK_OVERVIEW_OUTLET_FILTER);
        $machine_id = session()->get(OUTLET_STOCK_OVERVIEW_MACHINE_FILTER);

        $outlets = getOutlets();
        $machines = getMachines();

        $outletstockoverviews = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')
        ->whereNotNull('item_code')
        ->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id');
        if($outlet_id) {
            $outletstockoverviews = $outletstockoverviews->where('outlet_stock_overviews.outlet_id', $outlet_id);
        }
        if($machine_id) {
            $outletstockoverviews = $outletstockoverviews->where('outlet_stock_overviews.machine_id', $machine_id);
        }

        $outletstockoverviews = $outletstockoverviews->get();
        return view('reports.outletstockoverview', compact('outletstockoverviews', 'breadcrumbs', 'outlets', 'machines'));
    }
    
    public function exportOutletstockoverview() {
        $outletstockoverviews = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')
        ->whereNotNull('item_code')
        ->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id')
        ->get();
        return Excel::download(new OutletstockoverviewsExport($outletstockoverviews), 'outletstockoverview.xlsx');
    }

    public function search(Request $request){
        session()->start();
        session()->put('OUTLET_STOCK_OVERVIEW_OUTLET_FILTER', $request->outlet_id);
        session()->put('OUTLET_STOCK_OVERVIEW_MACHINE_FILTER', $request->machine_id);
        return redirect()->route('report.outletstockoverview');
    }

    public function reset(){
        session()->forget('OUTLET_STOCK_OVERVIEW_OUTLET_FILTER');
        session()->forget('OUTLET_STOCK_OVERVIEW_MACHINE_FILTER');
        return redirect()->route('report.outletstockoverview');
    }

    public function bodanddepartmentReport(){

        $breadcrumbs = [
            // ['name' => 'Outlets', 'url' => route('distribute.index')],
            ['name' => 'Bod & Department Report']
        ];

        $outlets = getOutlets(true);
        $tooutlets = [BODID => 'BOD', DEPID => 'Department'];
        // return $outlets;
        $sizeVariants = getSizeVariants();
        $from_outlet = session()->get(PD_FROMOUTLET_FILTER);
        $to_outlet = session()->get(PD_TOOUTLET_FILTER);
        $item_code = session()->get(PD_ITEMCODE_FILTER);
        $from_date = session()->get(PD_FROMDATE_FILTER);
        $to_date = session()->get(PD_TODATE_FILTER);
        $size_variant = session()->get(PD_SIZEVARIANT_FILTER);
        $purchase_price = session()->get(PD_PURCHASEPRICE_FILTER);
        $vouncher_no = session()->get(PD_VOUNCHERNO_FILTER);

        $distributes = distributes::select('distributes.*','distribute_products.quantity','distribute_products.purchased_price','distribute_products.subtotal','variations.item_code','variations.image','size_variants.value')
        ->join('distribute_products','distributes.id','=','distribute_products.distribute_id')
        ->join('variations','variations.id','=','distribute_products.variant_id')
        ->join('size_variants' , 'size_variants.id', '=', 'variations.size_variant_value');

        if($from_outlet){
            $distributes = $distributes->where('from_outlet', $from_outlet);        
        }

        if($to_outlet){
            $distributes = $distributes->where('to_outlet', $to_outlet);
        }

        if($item_code){
            $distributes = $distributes->where('item_code', $item_code);
        }

        if($from_date){
            $distributes = $distributes->where('date', '>=', $from_date);
        }

        if($to_date){
            $distributes = $distributes->where('date', '<=', $to_date);
        }

        if($size_variant){
            $distributes = $distributes->where('size_variants.id', $size_variant);
        }

        if($purchase_price){
            $distributes = $distributes->where('distribute_products.purchased_price', $purchase_price);
        }

        if($vouncher_no){
            $distributes = $distributes->where('vouncher_no', $vouncher_no);
        }

        $distributes = $distributes->whereIn('to_outlet', [BODID, DEPID])->get();

        // return $distributes;

        return view('reports.bodanddepartment',compact('breadcrumbs','distributes','outlets', 'tooutlets', 'sizeVariants'));
    }
}
