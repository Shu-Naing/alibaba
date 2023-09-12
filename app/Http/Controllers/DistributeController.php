<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Outlets;
use App\Models\Variation;
use App\Models\OutletItem;
use App\Models\distributes;
use Illuminate\Http\Request;
use App\Models\OutletItemData;
use App\Models\DistributeProducts;
use App\Models\OutletlevelHistory;
use App\Models\OutletLevelOverview;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListDistributeDetailExport;
use App\Exports\BodAndDepartmentExport;

class DistributeController extends Controller
{
    public function index()
    {

        $breadcrumbs = [
            ['name' => 'Distribute']
        ];

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;

        $from_date = session()->get(DISTRIBUTE_FROMDATE_FILTER);
        $to_date = session()->get(DISTRIBUTE_TODATE_FILTER);
        $from_outlet = session()->get(DISTRIBUT_FROMOUTLET_FILTER);
        $to_outlet = session()->get(DISTRIBUT_TOOUTLET_FILTER);
        $vouncher_no = session()->get(DISTRIBUT_VOUNCHERNO_FILTER);
        $status = session()->get(DISTRIBUT_STATUS_FILTER);

        
        $outlets = getOutlets(true);
        $distributes = distributes::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })
        ->when($from_outlet, function ($query) use ($from_outlet) {
            return $query->where('from_outlet', '=', $from_outlet);
        })
        ->when($to_outlet, function ($query) use ($to_outlet) {
            return $query->where('to_outlet', '=', $to_outlet);
        })
        ->when($vouncher_no, function ($query) use ($vouncher_no) {
            return $query->where('vouncher_no', '=', $vouncher_no);
        })                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
        ->when($status, function ($query) use ($status) {
            return $query->where('status', '=', $status);
        })
        ->when($login_user_role == 'Outlet', function ($query) use ($login_user_outlet_id){
            return $query->where('from_outlet', '=', $login_user_outlet_id)->orWhere('to_outlet', '=', $login_user_outlet_id);
        })    
        ->get();
        
        // return $distributes;
        return view('distribute.index',compact('breadcrumbs','distributes','outlets'));
    }

    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Distribute', 'url' => route('distribute.index')],
              ['name' => 'Create']
        ];
        $from_outlets = getFromOutlets(true);
        $outlets = getOutlets();
        $latestRef = distributes::orderBy('created_at', 'desc')->value('reference_No');
        $generatedRef = refGenerateCode($latestRef);

        return view('distribute.create', compact('breadcrumbs', 'outlets','generatedRef','from_outlets'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // return $data;
        $item_arr = [];

            $this->validate($request, [
                'date' => 'required',
                'reference_No' => 'required|unique:distributes',
                'from_outlet' =>'required',
                'to_outlet' =>'required',
            ]);
            $input = $request->only('date', 'reference_No', 'from_outlet', 'to_outlet','remark');
            $input['vouncher_no'] = str_replace(' ','-',get_outlet_name($request->from_outlet)) . '-' . $request->reference_No;
            $input['status'] = DS_PENDING;
            $input['created_by'] = Auth::user()->id;

            $distribute = distributes::create($input);
            $distributeId = $distribute->id;

        foreach($data as $key => $value) {
            $key_arr = explode("_", $key);
            if(count($key_arr) > 1){
                if($key_arr[1] == 'qtyNumber'){ 
                    $item_arr[$key_arr[0]] = $value;    
                }
            }
        }
        
        foreach($item_arr as $key => $value){
            $variation = Variation::where('item_code', $key)->first();
            $fromOutletItemData = outlet_item_data($request->from_outlet,$variation->id);
            DistributeProducts::create([
                'distribute_id' => $distributeId,
                'variant_id' => $variation->id,
                'purchased_price' => $fromOutletItemData->purchased_price,
                'subtotal' => $fromOutletItemData->purchased_price * $value,
                'remark' => $request->remark,
                'created_by' => Auth::user()->id,
                'quantity' => $value,
            ]);
        }
        
        return redirect()->back()->with('success','Distribute created successfully.');
    }    
    public function show($id)
    {
        $distribute = [];
        $breadcrumbs = [
            ['name' => 'Reports', 'url' => route('distribute.index')],
            ['name' => 'Detail Distribute Products']
        ];
        $outlets = getOutlets();
        $users = getUser();
        $distribute_data = distributes::find($id);
        $distribute_products_data = distributes::select('distribute_products.id', 'distribute_products.quantity','distribute_products.purchased_price','distribute_products.subtotal','variations.item_code','variations.image','size_variants.value')
        ->join('distribute_products','distributes.id','=','distribute_products.distribute_id')
        ->join('variations','variations.id','=','distribute_products.variant_id')
        ->join('size_variants' , 'size_variants.id', '=', 'variations.size_variant_value')
        ->where('distributes.id', $id)
        ->get();

        $distribute['distribute'] = $distribute_data;
        $distribute['distribute_products_data'] = $distribute_products_data;

        return view('distribute.show',compact('distribute','breadcrumbs','outlets', 'users'));
    }   
    public function edit($id,$from_outlet)
    {
        $breadcrumbs = [
              ['name' => 'Distribute Products', 'url' => route('distribute.index')],
              ['name' => 'Create']
        ];

        $outlets = getOutlets();
        $distribute = distributes::findorFail($id);
        $distribute_products = DistributeProducts::select("distribute_products.*", "products.product_name", "variations.item_code")->join("variations", "variations.id", "=", "distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("distribute_id", $id)->get();

        $outletitems = OutletItem::where('outlet_id', $from_outlet)->get();
        $variant_qty = [];
        foreach ($outletitems as $outletitem) {
            $variant_qty[$outletitem->variation_id] = outlet_item_data($outletitem->outlet_id,$outletitem->variation_id)->quantity;
        }
        return view('distribute.edit', compact('distribute','outlets', 'distribute_products', 'variant_qty', 'breadcrumbs'));
    }
   
    public function update(Request $request, $id)
    {
        $distribute = distributes::find($id);
        $errorItem = []; 
        $outlets = getOutlets();
        if($distribute->status !== DS_APPROVE ){
            $distribute->updated_by = Auth::user()->id;
            
            if($request->status == 'approve'){
                $distribute->status = DS_APPROVE;
                $distribute_products = DistributeProducts::where('distribute_id',$distribute->id)->get();
                foreach($distribute_products as $distribute_product){
                    $item_code = Variation::where('id',$distribute_product->variant_id)->value('item_code');
                    $fromOutletItemData = outlet_item_data($distribute->from_outlet,$distribute_product->variant_id);
                    if( ($fromOutletItemData->quantity - $distribute_product->quantity) >= 0 ) {
                        OutletlevelHistory::create([
                            'outlet_id' => $distribute->from_outlet,
                            'type' => ISSUE_TYPE,
                            'quantity' => $distribute_product->quantity,
                            'item_code' => $item_code,
                            'branch' => $outlets[$distribute->to_outlet],
                            'date' => $distribute->date,
                            'remark' => $distribute->remark,
                            'created_by' => Auth::user()->id,
                            'remark' => $distribute->remark,
                        ]);
        
                        OutletlevelHistory::create([
                            'outlet_id' => $distribute->to_outlet,
                            'type' => RECIEVE_TYPE,
                            'quantity' => $distribute_product->quantity,
                            'item_code' => $item_code,
                            'branch' => $outlets[$distribute->from_outlet],
                            'date' => $distribute->date,
                            'remark' => $distribute->remark,
                            'created_by' => Auth::user()->id,
                            'remark' => $distribute->remark,
                        ]);

                        $outletitem = OutletItem::where('outlet_id',$distribute->to_outlet)->where('variation_id',$distribute_product->variant_id)->first();
                        if($outletitem){
                            $outletitem->updated_by = Auth::user()->id;
                            $outletitem->update();
        
                            OutletItemData::create([
                                'outlet_item_id' => $outletitem->id,
                                'purchased_price' => $fromOutletItemData->purchased_price,
                                'points' => $fromOutletItemData->points,
                                'tickets' => $fromOutletItemData->tickets,
                                'kyat' => $fromOutletItemData->kyat, 
                                'quantity' => $distribute_product->quantity,
                                'created_by' => Auth::user()->id,
                            ]);
                        }else{
                            $outlet_item = OutletItem::create([
                                'outlet_id' => $distribute->to_outlet,
                                'variation_id' => $distribute_product->variant_id,
                                'created_by' => Auth::user()->id,
                            ]);
        
                            OutletItemData::create([
                                'outlet_item_id' => $outlet_item->id,
                                'purchased_price' => $fromOutletItemData->purchased_price,
                                'points' => $fromOutletItemData->points,
                                'tickets' => $fromOutletItemData->tickets,
                                'kyat' => $fromOutletItemData->kyat, 
                                'quantity' => $distribute_product->quantity,
                                'created_by' => Auth::user()->id,
                            ]);
                        }

                        $fromOutletItemData->quantity = $fromOutletItemData->quantity - $distribute_product->quantity;
                        $fromOutletItemData->update();

                        $month = date('n', strtotime($distribute->date));
                        $year = date('Y', strtotime($distribute->date));
                        $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
                        ->where('outlet_id', $distribute->from_outlet)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('item_code',$item_code)->first();

                        if($outletleveloverview){ 
                            $issued_qty = $outletleveloverview->issued_qty + $distribute_product->quantity;    
                            $input = [];
                            $input['issued_qty'] = $issued_qty;
                            $input['balance'] = ($outletleveloverview->opening_qty + $outletleveloverview->receive_qty) - $issued_qty;
                            $input['updated_by'] = Auth::user()->id;
                            $outletleveloverview->update($input);
                        }else {
                            $input = [];
                            $input['date'] = $distribute->date;
                            $input['outlet_id'] = $distribute->from_outlet;
                            $input['item_code'] = $item_code;
                            $input['issued_qty'] = $distribute_product->quantity;
                            $input['balance'] = (0 + 0) - $distribute_product->quantity;
                            $input['created_by'] = Auth::user()->id;
                            OutletLevelOverview::create($input);
                        }
                        // from outlet for outletleveloverview end

                        // to outlet for outletleveloverview start
                        $month = date('n', strtotime($distribute->date));
                        $year = date('Y', strtotime($distribute->date));
                        $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
                        ->where('outlet_id', $distribute->to_outlet)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->where('item_code',$item_code)->first();

                        if($outletleveloverview){     
                            $input = [];
                            $input['receive_qty'] = $outletleveloverview->receive_qty + $distribute_product->quantity;
                            $input['balance'] = ($outletleveloverview->opening_qty + $input['receive_qty']) - $outletleveloverview->issued_qty;
                            $input['updated_by'] = Auth::user()->id;
                            $outletleveloverview->update($input);
                        }else {
                            $input = [];
                            $input['date'] = $distribute->date;
                            $input['outlet_id'] = $distribute->to_outlet;
                            $input['item_code'] = $item_code;
                            $input['receive_qty'] = $distribute_product->quantity;
                            $input['balance'] = $distribute_product->quantity;
                            $input['created_by'] = Auth::user()->id;
                            OutletLevelOverview::create($input);
                        }
                    }else {
                        $errorItem[] = $item_code;
                    }
                }
                    

            }else{
                $distribute->status = DS_REJECT;  
            }
           
        } 
        $distribute->updated_by = Auth::user()->id;
        $distribute->update();

        return response()->json(['message' => 'Success', 'errorItem' => $errorItem]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function preview($distribute_id){

        $distribute = distributes::whereHas('distribute_porducts', function ($query) use ($distribute_id){
            $query->where('distribute_id',$distribute_id);
        })->with('distribute_porducts.variant.product.unit')->first();

        return view('distribute.preview',compact('distribute'));
    }

    public function listdistributedetail() {

        $breadcrumbs = [
            ['name' => 'Outlets', 'url' => route('distribute.index')],
            ['name' => 'Distribute Detail']
        ];
        $outlets = getOutlets(true);
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

        $auth_outlet = Auth::user()->outlet_id;

        if(is_outlet_user()){
            $distributes = $distributes->orWhere('to_outlet', $auth_outlet);
            $distributes = $distributes->orwhere('from_outlet', $auth_outlet);
        }

        $distributes = $distributes->whereNotIn('to_outlet', [BODID, DEPID]);

        $distributes = $distributes->whereNotIn('from_outlet', [BODID, DEPID])->get();

        return view('distribute.listdistributedetail',compact('breadcrumbs','distributes','outlets', 'sizeVariants'));
    }


    public function distributeDetailExport(){

        $outlets = getOutlets(true);
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

        $distributes = $distributes->whereNotIn('to_outlet', [BODID, DEPID]);

        $distributes = $distributes->whereNotIn('from_outlet', [BODID, DEPID])->get();

        return Excel::download(new ListDistributeDetailExport($distributes,$outlets), 'distribute-detail.xlsx');
    }

    public function bodanddepartmentExport(){

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

        return Excel::download(new BodAndDepartmentExport($distributes,$outlets, $tooutlets), 'bodanddepartment.xlsx');
    }

    public function updatedistributeproductdetailqty (Request $request) {
        // return 'helo';
        $distributeProduct = DistributeProducts::find($request->id);
        $subtotal = $distributeProduct->purchased_price * $request->qty;
        // return $distributeProduct;
        if ($distributeProduct) {
            $distributeProduct->update([
                'quantity' => $request->qty,
                'subtotal' => $subtotal
            ]);
        }
        return "sucees data";
    } 
}
