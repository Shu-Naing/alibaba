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

class DistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
                ['name' => 'Distribute']
        ];
        $outlets = getOutlets();

        // $distributes = DistributeProducts::join('distributes','distributes.id','=','distribute_products.distribute_id')->get();
        $distributes = distributes::all();

        // return $distributes;
        return view('distribute.index',compact('breadcrumbs','distributes','outlets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        
        $breadcrumbs = [
              ['name' => 'Distribute', 'url' => route('distribute.index')],
              ['name' => 'Create']
        ];
        $outlets = getOutlets();
        // return $outlets;
        $latestRef = distributes::orderBy('created_at', 'desc')->value('reference_No');
        
        $generatedRef = refGenerateCode($latestRef);

        // return $generatedRef;

        return view('distribute.create', compact('breadcrumbs', 'outlets','generatedRef'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $data = $request->all();
        $item_arr = [];

        // create distribute table start
            $this->validate($request, [
                'date' => 'required',
                'reference_No' => 'required|unique:distributes',
                'status' => 'required',
                'from_outlet' =>'required',
                'to_outlet' =>'required',
            ]);
            $input = $request->only('date', 'reference_No', 'status', 'from_outlet', 'to_outlet','remark');
            $input['created_by'] = Auth::user()->id;

            $distribute = distributes::create($input);
            $distributeId = $distribute->id;
            // return $data
        // create distribute table end

        foreach($data as $key => $value) {
            $key_arr = explode("_", $key);
            if(count($key_arr) > 1){
                if($key_arr[1] == 'qtyNumber'){ 
                    $item_arr[$key_arr[0]] = $value;    
                }
            }
        }
        
        foreach($item_arr as $key => $value){
            // return $row;
            $variation = Variation::where('item_code', $key)->first();
            $fromOutletItemData = outlet_item_data($request->from_outlet,$variation->id);

            // distribute product create start
                // $input = [];
                // $input['distribute_id'] = $distributeId;
                // $input['variant_id'] = $variation->id;
                // $input['purchased_price'] = $variation->purchased_price;
                // $input['subtotal'] = $variation->purchased_price * $value;
                // $input['remark'] = $request->remark;
                // $input['created_by'] = Auth::user()->id;
                // $input['quantity'] = $value;
                // DistributeProducts::create($input);

                DistributeProducts::create([
                    'distribute_id' => $distributeId,
                    'variant_id' => $variation->id,
                    'purchased_price' => $fromOutletItemData->purchased_price,
                    'subtotal' => $fromOutletItemData->purchased_price * $value,
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                    'quantity' => $value,
                ]);
            // distribute product create end
            
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
                // }

                $outletitem = OutletItem::where('outlet_id',$request->to_outlet)->where('variation_id',$variation->id)->first();
                if($outletitem){
                    $outletitem->updated_by = Auth::user()->id;
                    $outletitem->update();

                    OutletItemData::create([
                        'outlet_item_id' => $outletitem->id,
                        'purchased_price' => $fromOutletItemData->purchased_price,
                        'points' => $fromOutletItemData->points,
                        'tickets' => $fromOutletItemData->tickets,
                        'kyat' => $fromOutletItemData->kyat, 
                        'quantity' => $value,
                        'created_by' => Auth::user()->id,
                    ]);
                }else{
                    $outlet_item = OutletItem::create([
                        'outlet_id' => $request->to_outlet,
                        'variation_id' => $variation->id,
                        'created_by' => Auth::user()->id,
                    ]);

                    OutletItemData::create([
                        'outlet_item_id' => $outlet_item->id,
                        'purchased_price' => $fromOutletItemData->purchased_price,
                        'points' => $fromOutletItemData->points,
                        'tickets' => $fromOutletItemData->tickets,
                        'kyat' => $fromOutletItemData->kyat, 
                        'quantity' => $value,
                        'created_by' => Auth::user()->id,
                    ]);
                }
            // need to change for fifo tech end // to outlet add product item end

            //create $input with outlet_itmes_tbl columns
            
            // from formoutlet add product item start
                //get main inventory qty  with variant_id and main outlet id
                // $from_outlet_item = OutletItem::select('quantity')
                //     ->where('outlet_id', $request->from_outlet)
                //     ->where('variation_id', $variation->id)->first();
                
                // $qty = $from_outlet_item->quantity - $value;
                
                //update outlet_items_tbl with main outlet id 
                // $fromOutlet = OutletItem::where('outlet_id', $request->from_outlet)->where('variation_id', $variation->id)->first();
                // $input = [];
                // $input['quantity'] = $qty;
                // $fromOutlet->update($input); 

                $fromOutletItemData->quantity = $fromOutletItemData->quantity - $value;
                $fromOutletItemData->update();
            // from formoutlet add product item end

        }
        
        // return redirect('distribute.edit')->view(, compact('distribute','outlets'));
        // return redirect()->route('distribute.edit', ['id' => $distributeId, 'from_outlet'=>$distribute->from_outlet]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $distribute = [];
        $breadcrumbs = [
            ['name' => 'Reports', 'url' => route('distribute.index')],
            ['name' => 'Detail Distribute Products']
        ];
        $outlets = getOutlets();
        $distribute_data = distributes::find($id);
        $distribute_products_data = distributes::select('distribute_products.quantity','distribute_products.purchased_price','distribute_products.subtotal','variations.item_code','variations.image','variations.value')
        ->join('distribute_products','distributes.id','=','distribute_products.distribute_id')
        ->join('variations','variations.id','=','distribute_products.variant_id')
        ->where('distributes.id',$id)
        ->get();

        $distribute['distribute'] = $distribute_data;
        $distribute['distribute_products_data'] = $distribute_products_data;        

        return view('distribute.show',compact('distribute','breadcrumbs','outlets'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $input = $request->all();
        $inputs['updated_by'] = Auth::user()->id;
        $distribute = distributes::find($id);
        $distribute->update($input);

        //select distribute product with distribute id
        // $distribute_products = array();
        // $distribute_products = DistributeProducts::where('distribute_id', $id)->get();
        // return $distribute_products;
        // foreach($distribute_products as $row){
        //     // return $row;
        //     $input = [];
        //     $input['outlet_id'] = $request->to_outlet;
        //     $input['variation_id'] = $row->variant_id;
        //     $input['created_by'] = Auth::user()->id;

        //     $outletitem = OutletItem::select('quantity')->where('outlet_id', $request->to_outlet)->where('variation_id', $row->variant_id)->first();
        //     if($outletitem) {
        //         $input['quantity'] = $row->quantity + $outletitem->quantity;
        //         $outletitem->update($input);
        //     }else {
        //         $input['quantity'] = $row->quantity;
        //         OutletItem::create($input);
        //     }
        //     //create $input with outlet_itmes_tbl columns
            
        //     //get main inventory qty  with variant_id and main outlet id
        //     $main_inv_qty = OutletItem::select('quantity')
        //         ->where('outlet_id', MAIN_INV_ID)
        //         ->where('variation_id', $row->variant_id)->first();
            
        //     $qty = $main_inv_qty->quantity - $row->quantity;
            
        //     //update outlet_items_tbl with main outlet id 
        //     $mainOutlet = OutletItem::where('outlet_id', MAIN_INV_ID)->where('variation_id', $row->variant_id)->first();
        //     $input = [];
        //     $input['quantity'] = $qty;
        //     $mainOutlet->update($input);  

        // }
        return redirect()->route('distribute.index')
            ->with('success','Distribute process successfully');
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

        // return $distribute;
        
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

        $distributes = distributes::select('distributes.*','distribute_products.quantity','distribute_products.purchased_price','distribute_products.subtotal','variations.item_code','variations.image','variations.value')
        ->join('distribute_products','distributes.id','=','distribute_products.distribute_id')
        ->join('variations','variations.id','=','distribute_products.variant_id');

        if($from_outlet){
            $distributes = $distributes->where('from_outlet', $from_outlet);        
        }

        if($to_outlet){
            $distributes = $distributes->where('to_outlet', $to_outlet);
        }

        if($item_code){
            $distributes = $distributes->where('item_code', $item_code);
        }
     
        $distributes = $distributes->get();

        // return $item_code."hellos";

        return view('distribute.listdistributedetail',compact('breadcrumbs','distributes','outlets'));
    }
}
