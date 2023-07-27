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

class DistributeController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
                ['name' => 'Distribute']
        ];
        $outlets = getOutlets();
        $distributes = distributes::all();

        return view('distribute.index',compact('breadcrumbs','distributes','outlets'));
    }

    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Distribute', 'url' => route('distribute.index')],
              ['name' => 'Create']
        ];
        $outlets = getOutlets();
        $latestRef = distributes::orderBy('created_at', 'desc')->value('reference_No');
        $generatedRef = refGenerateCode($latestRef);

        return view('distribute.create', compact('breadcrumbs', 'outlets','generatedRef'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $item_arr = [];

            $this->validate($request, [
                'date' => 'required',
                'reference_No' => 'required|unique:distributes',
                'from_outlet' =>'required',
                'to_outlet' =>'required',
            ]);
            $input = $request->only('date', 'reference_No', 'from_outlet', 'to_outlet','remark');
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


//     start ko ye zaw code 
//                 OutletlevelHistory::create([
//                     'outlet_id' => $request->from_outlet,
//                     'type' => ISSUE_TYPE,
//                     'quantity' => $value,
//                     'item_code' => $key,
//                     'branch' => $request->to_outlet,
//                     'date' => $request->date,
//                     'remark' => $request->remark,
//                     'created_by' => Auth::user()->id,
//                 ]);

//                 OutletlevelHistory::create([
//                     'outlet_id' => $request->to_outlet,
//                     'type' => RECIEVE_TYPE,
//                     'quantity' => $value,
//                     'item_code' => $key,
//                     'branch' => $request->from_outlet,
//                     'date' => $request->date,
//                     'remark' => $request->remark,
//                     'created_by' => Auth::user()->id,
//                 ]);
//             // distribute product create end

//             // from outlet for outletleveloverview start
//                 $month = date('m', strtotime($request->date));
//                 $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
//                 ->where('outlet_id', $request->from_outlet)
//                 ->whereMonth('date', $month)
//                 ->where('item_code',$key)->first();

//                 if($outletleveloverview){     
//                     $input = [];
//                     $input['issued_qty'] = $outletleveloverview->issued_qty + $value;
//                     $input['balance'] = ($outletleveloverview->opening_qty + $outletleveloverview->receive_qty) - $input['issued_qty'];
//                     $input['updated_by'] = Auth::user()->id;
//                     $outletleveloverview->update($input);
//                 }else {
//                     $input = [];
//                     $input['date'] = $request->date;
//                     $input['outlet_id'] = $request->from_outlet;
//                     $input['item_code'] = $key;
//                     $input['issued_qty'] = $value;
//                     $input['balance'] = (0 + 0) - $value;
//                     $input['created_by'] = Auth::user()->id;
//                     OutletLevelOverview::create($input);
//                 }
//             // from outlet for outletleveloverview end

//             // to outlet for outletleveloverview start
//                 $month = date('m', strtotime($request->date));
//                 $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
//                 ->where('outlet_id', $request->to_outlet)
//                 ->whereMonth('date', $month)
//                 ->where('item_code',$key)->first();

//                 if($outletleveloverview){     
//                     $input = [];
//                     $input['receive_qty'] = $outletleveloverview->receive_qty + $value;
//                     $input['balance'] = ($outletleveloverview->opening_qty + $input['receive_qty']) - $outletleveloverview->issued_qty;
//                     $input['updated_by'] = Auth::user()->id;
//                     $outletleveloverview->update($input);
//                 }else {
//                     $input = [];
//                     $input['date'] = $request->date;
//                     $input['outlet_id'] = $request->to_outlet;
//                     $input['item_code'] = $key;
//                     $input['receive_qty'] = $value;
//                     $input['balance'] = $value;
//                     $input['created_by'] = Auth::user()->id;
//                     OutletLevelOverview::create($input);
//                 }
  //end ko ye zaw code
            // to outlet for outletleveloverview end


            
            // need to change for fifo tech start // to outlet add product item start


                // $input = [];
                // $input['outlet_id'] = $request->to_outlet;
                // $input['variation_id'] = $variation->id;
                // $input['created_by'] = Auth::user()->id;

                // $outletitem = OutletItem::select('quantity')->where('outlet_id', $request->to_outlet)->where('variation_id', $variation->id)->first();
                // if($outletitem) {
                //     $input['quantity'] = $value + $outletitem->quantity;
                //     $outletitem->update($input);
                // }else {
                //     $input['quantity'] = $value;
                //     OutletItem::create($input);
  
                // OutletlevelHistory::create([
                //     'outlet_id' => $request->from_outlet,
                //     'type' => ISSUE_TYPE,
                //     'quantity' => $value,
                //     'item_code' => $key,
                //     'branch' => $request->to_outlet,
                //     'date' => $request->date,
                //     'remark' => $request->remark,
                //     'created_by' => Auth::user()->id,
                //     'remark' => $request->remark,
                // ]);

                // OutletlevelHistory::create([
                //     'outlet_id' => $request->to_outlet,
                //     'type' => RECIEVE_TYPE,
                //     'quantity' => $value,
                //     'item_code' => $key,
                //     'branch' => $request->from_outlet,
                //     'date' => $request->date,
                //     'remark' => $request->remark,
                //     'created_by' => Auth::user()->id,
                //     'remark' => $request->remark,
                // ]);
         

                // $outletitem = OutletItem::where('outlet_id',$request->to_outlet)->where('variation_id',$variation->id)->first();
                // if($outletitem){
                //     $outletitem->updated_by = Auth::user()->id;
                //     $outletitem->update();

                //     OutletItemData::create([
                //         'outlet_item_id' => $outletitem->id,
                //         'purchased_price' => $fromOutletItemData->purchased_price,
                //         'points' => $fromOutletItemData->points,
                //         'tickets' => $fromOutletItemData->tickets,
                //         'kyat' => $fromOutletItemData->kyat, 
                //         'quantity' => $value,
                //         'created_by' => Auth::user()->id,
                //     ]);
                // }else{
                //     $outlet_item = OutletItem::create([
                //         'outlet_id' => $request->to_outlet,
                //         'variation_id' => $variation->id,
                //         'created_by' => Auth::user()->id,
                //     ]);

                //     OutletItemData::create([
                //         'outlet_item_id' => $outlet_item->id,
                //         'purchased_price' => $fromOutletItemData->purchased_price,
                //         'points' => $fromOutletItemData->points,
                //         'tickets' => $fromOutletItemData->tickets,
                //         'kyat' => $fromOutletItemData->kyat, 
                //         'quantity' => $value,
                //         'created_by' => Auth::user()->id,
                //     ]);

                // }
        

                // $fromOutletItemData->quantity = $fromOutletItemData->quantity - $value;
                // $fromOutletItemData->update();
          

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
        $distribute_data = distributes::find($id);
        $distribute_products_data = distributes::select('distribute_products.quantity','distribute_products.purchased_price','distribute_products.subtotal','variations.item_code','variations.image','size_variants.value')
        ->join('distribute_products','distributes.id','=','distribute_products.distribute_id')
        ->join('variations','variations.id','=','distribute_products.variant_id')
        ->join('size_variants' , 'size_variants.id', '=', 'variations.size_variant_value')
        ->where('distributes.id',1)
        ->get();

        $distribute['distribute'] = $distribute_data;
        $distribute['distribute_products_data'] = $distribute_products_data;        

        return view('distribute.show',compact('distribute','breadcrumbs','outlets'));
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
        if($distribute->status !== 2 ){
            $distribute->updated_by = Auth::user()->id;
            if($request->status == 'approve'){
                $distribute->status = DS_APPROVE;
                $distribute_products = DistributeProducts::where('distribute_id',$distribute->id)->get();
                foreach($distribute_products as $distribute_product){
                    $item_code = Variation::where('id',$distribute_product->variant_id)->value('item_code');
                    $fromOutletItemData = outlet_item_data($distribute->from_outlet,$distribute_product->variant_id);
                    OutletlevelHistory::create([
                        'outlet_id' => $distribute->from_outlet,
                        'type' => ISSUE_TYPE,
                        'quantity' => $distribute_product->quantity,
                        'item_code' => $item_code,
                        'branch' => $distribute->to_outlet,
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
                        'branch' => $distribute->from_outlet,
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

                    $month = date('m', strtotime($distribute->date));
                    $year = date('Y', strtotime($request->date));
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
                    $month = date('m', strtotime($distribute->date));
                    $year = date('Y', strtotime($request->date));
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
                }

            }else{
                $distribute->status = DS_REJECT;  
            }
           
        }
        $distribute->updated_by = Auth::user()->id;
        $distribute->update();

        return response()->json(['message' => 'Success']);
        
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
        $outlets = getOutlets();
        $from_outlet = session()->get(PD_FROMOUTLET_FILTER);
        $to_outlet = session()->get(PD_TOOUTLET_FILTER);
        $item_code = session()->get(PD_ITEMCODE_FILTER);
        $date = session()->get(PD_DATE_FILTER);

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

        if($date){
            $distributes = $distributes->whereDate('date',$date);
        }
     
        $distributes = $distributes->get();

        return view('distribute.listdistributedetail',compact('breadcrumbs','distributes','outlets'));
    }


    public function distributeDetailExport(){

        $outlets = getOutlets();
        $from_outlet = session()->get(PD_FROMOUTLET_FILTER);
        $to_outlet = session()->get(PD_TOOUTLET_FILTER);
        $item_code = session()->get(PD_ITEMCODE_FILTER);
        $date = session()->get(PD_DATE_FILTER);

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

        if($date){
            $distributes = $distributes->whereDate('date',$date);
        }
     
        $distributes = $distributes->get();

        return Excel::download(new ListDistributeDetailExport($distributes,$outlets), 'distribute-detail.xlsx');
    }
}
