<?php

namespace App\Http\Controllers;

use App\Models\Outlets;
use App\Models\Variation;
use App\Models\distributes;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\DB;
use App\Models\OutletStockOverview;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PurchasedPriceHistory;
use Illuminate\Support\Facades\Session;
use App\Exports\PriceChangedHistoryExport;
use App\Exports\OutletstockoverviewsExport;



class ReportController extends Controller
{
    public function productReport(){

        $breadcrumbs = [
            ['name' => 'Report Products', 'url' => route('report.products')],
        ];

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;

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

        if($login_user_role == 'Outlet'){
            $outlets = Outlets::with('machines')->where('id',$login_user_outlet_id)->get();
        }else{
            $outlets = Outlets::with('machines')->where('id','!=',1)->get();
        }
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

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;

        $outlet_id = session()->get(OUTLET_STOCK_OVERVIEW_OUTLET_FILTER);
        $machine_id = session()->get(OUTLET_STOCK_OVERVIEW_MACHINE_FILTER);

        $outlets = getFromOutlets(true);
        $machines = getMachines();

        $outletstockoverviews = DB::table('outlet_stock_overviews as oso')
        ->select(
            'oso.*',
            'oi.id as outlet_item_id',
            'oid.points','oid.tickets','oid.kyat','oid.purchased_price',
            'machines.name',
            'variations.image','variations.size_variant_value',
            'products.unit_id','products.category_id'
        )
        ->join('machines', 'machines.id', '=', 'oso.machine_id')
        ->join('variations', 'variations.item_code', '=', 'oso.item_code')
        ->join('products', 'products.id', '=', 'variations.product_id')
        ->join('outlet_items as oi', 'oi.variation_id', '=', 'variations.id')
        ->join(DB::raw('(SELECT oid1.*
                        FROM outlet_item_data oid1
                        WHERE oid1.id IN (SELECT MAX(oid2.id)
                                        FROM outlet_item_data oid2
                                        GROUP BY oid2.outlet_item_id)) as oid'), function ($join) {
            $join->on('oid.outlet_item_id', '=', 'oi.id');
        })
        ->whereNotNull('oso.item_code')
        ->groupBy(
            'oso.id', // Add 'oso.id' to the GROUP BY clause
            'oso.item_code',
            DB::raw('MONTH(oso.date)'),
            DB::raw('YEAR(oso.date)'),
            'oso.outlet_id'
        )
        ->orderBy('oso.date', 'DESC');
        if($outlet_id) {
            $outletstockoverviews = $outletstockoverviews->where('oso.outlet_id', $outlet_id);
        }
        if($machine_id) {
            $outletstockoverviews = $outletstockoverviews->where('oso.machine_id', $machine_id);
        }

        if($login_user_role == 'Outlet'){
            $outletstockoverviews = $outletstockoverviews->where('oso.outlet_id', $login_user_outlet_id);
        }

        $outletstockoverviews = $outletstockoverviews->get();

        $size_variants = getSizeVariants();
        $units = getUnits();
        $categories = getCategories();
       
        return view('reports.outletstockoverview', compact('outletstockoverviews', 'breadcrumbs', 'outlets', 'machines','size_variants','units',
    'categories'));
    }
    
    public function exportOutletstockoverview() {
        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;

        $outlet_id = session()->get(OUTLET_STOCK_OVERVIEW_OUTLET_FILTER);
        $machine_id = session()->get(OUTLET_STOCK_OVERVIEW_MACHINE_FILTER);

        $outlets = getFromOutlets(true);
        $machines = getMachines();

        $outletstockoverviews = DB::table('outlet_stock_overviews as oso')
        ->select(
            'oso.*',
            'oi.id as outlet_item_id',
            'oid.points','oid.tickets','oid.kyat','oid.purchased_price',
            'machines.name'
        )
        ->join('machines', 'machines.id', '=', 'oso.machine_id')
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
        ->groupBy(
            'oso.id', // Add 'oso.id' to the GROUP BY clause
            'oso.item_code',
            DB::raw('MONTH(oso.date)'),
            DB::raw('YEAR(oso.date)'),
            'oso.outlet_id'
        )
        ->orderBy('oso.date', 'DESC');
        if($outlet_id) {
            $outletstockoverviews = $outletstockoverviews->where('oso.outlet_id', $outlet_id);
        }
        if($machine_id) {
            $outletstockoverviews = $outletstockoverviews->where('oso.machine_id', $machine_id);
        }

        if($login_user_role == 'Outlet'){
            $outletstockoverviews = $outletstockoverviews->where('oso.outlet_id', $login_user_outlet_id);
        }

        $outletstockoverviews = $outletstockoverviews->get();

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



    function priceChangeHistory(){
        $breadcrumbs = [
            ['name' => 'List Price Changed History', 'url' => route('report.price-changed-history')],
        ];

        $received_date = session()->get(PCH_RECEIVED_DATE_FILTER);
        $item_code = session()->get(PCH_ITEM_CODE_FILTER);

        $price_changed_histories = PurchasedPriceHistory::select('purchased_price_histories.variation_id','purchased_price_histories.points','purchased_price_histories.tickets',
        'purchased_price_histories.kyat','purchased_price_histories.purchased_price','variations.item_code',DB::raw('MAX(received_date) as received_date'))
        ->join('variations','purchased_price_histories.variation_id','=','variations.id')
        ->groupBy('purchased_price_histories.variation_id')
        ->groupBy('purchased_price_histories.points')
        ->groupBy('purchased_price_histories.tickets')
        ->groupBy('purchased_price_histories.kyat')
        ->groupBy('purchased_price_histories.purchased_price');

            if($received_date){
            $price_changed_histories = $price_changed_histories->where('purchased_price_histories.received_date',$received_date); 
        }

        if($item_code){
            $variation_id = Variation::where('item_code',$item_code)->value('id');
            $price_changed_histories= $price_changed_histories->where('purchased_price_histories.variation_id',$variation_id); 
        }

        $price_changed_histories= $price_changed_histories->get();
       
        return view('reports.price-changed-history',compact('breadcrumbs','price_changed_histories'));
    }


    function priceChangeHistoryExport(){

        $received_date = session()->get(PCH_RECEIVED_DATE_FILTER);
        $item_code = session()->get(PCH_ITEM_CODE_FILTER);

        $price_changed_histories = PurchasedPriceHistory::select('purchased_price_histories.variation_id','purchased_price_histories.points','purchased_price_histories.tickets',
        'purchased_price_histories.kyat','purchased_price_histories.purchased_price','variations.item_code',DB::raw('MAX(received_date) as received_date'))
        ->join('variations','purchased_price_histories.variation_id','=','variations.id')
        ->groupBy('purchased_price_histories.variation_id')
        ->groupBy('purchased_price_histories.points')
        ->groupBy('purchased_price_histories.tickets')
        ->groupBy('purchased_price_histories.kyat')
        ->groupBy('purchased_price_histories.purchased_price');

            if($received_date){
            $price_changed_histories = $price_changed_histories->where('purchased_price_histories.received_date',$received_date); 
        }

        if($item_code){
            $variation_id = Variation::where('item_code',$item_code)->value('id');
            $price_changed_histories= $price_changed_histories->where('purchased_price_histories.variation_id',$variation_id); 
        }

        $price_changed_histories= $price_changed_histories->get();
       
        return Excel::download(new PriceChangedHistoryExport($price_changed_histories), 'price_changed_histories.xlsx');
    }
}


