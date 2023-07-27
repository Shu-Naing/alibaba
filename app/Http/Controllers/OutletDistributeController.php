<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlets;
use App\Models\Counter;
use App\Models\Machines;
use App\Models\OutletDistribute;
use App\Models\OutletDistributeProduct;
use App\Models\OutletItem;
use App\Models\OutletStockHistory;
use App\Models\CounterVariant;
use App\Models\MachineVariant;
use App\Models\OutletStockOverview;
use App\Models\Variation;
use App\Models\OutletItemData;
use App\Models\OutletlevelHistory;
use App\Models\OutletLevelOverview;
use Auth;

class OutletDistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Outelts', 'url' => route('outletdistribute.index')],
              ['name' => 'List Recieve Distributes Product']
        ];
        $outlets = getOutlets();
        $machines = getMachines();
        $outletDistributes = OutletDistribute::all()->where('type', RECIEVE_TYPE);
        return view('outletdistribute.index', compact('outletDistributes', 'breadcrumbs', 'outlets', 'machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {   
        $outlets = getOutlets();
        $counter_machines = getMachinesWithOutletID($id);
        $counter = $counter_machines['counter'];
        $machines = $counter_machines['machine'];

        $latestRef = OutletDistribute::orderBy('created_at', 'desc')->value('reference_No');
        $generatedRef = refGenerateCode($latestRef);

        return view('outletdistribute.create', compact('outlets', 'id','counter', 'machines', 'generatedRef'));
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
        // return $data;
        $item_arr = [];
        
        $this->validate($request,[ 
            'date' => 'required',
            'reference_No' => 'required|unique:outlet_distributes',
            // 'status' => 'required',             
            'counterMachine' => 'required|in:1,2',
            'to_counter' => 'sometimes|required|required_if:counterMachine,1',
            'to_machine' => 'sometimes|required|required_if:counterMachine,2',
        ]);
        
        $input = $request->only('date','reference_no','from_outlet','reference_No', 'remark');
        if($request->counterMachine == 1){
            $input['to_machine'] = $request->to_counter;
        }else{
            $input['to_machine'] = $request->to_machine;
        }
        $input['counter_machine'] = $request->counterMachine;
        $input['type'] = RECIEVE_TYPE;
        $input['created_by'] = Auth::user()->id;
        $outletdistribute = OutletDistribute::create($input);
        $outletdistributeId = $outletdistribute->id;
        // return "success";


        foreach($data as $key => $value) {
            $key_arr = explode("_", $key);
            if(count($key_arr) > 1){
                if($key_arr[1] == 'qtyNumber'){ 
                    $item_arr[$key_arr[0]] = $value;    
                }
            }
        }


        if($request->counterMachine === "1") {
            // return "counter".$data;
            foreach($item_arr as $key => $value){
                $variation = Variation::where('item_code', $key)->first();
                $fromOutletItemData = outlet_item_data($request->from_outlet,$variation->id);
                // return $fromOutletItemData;
                // die();

                OutletDistributeProduct::create([
                    'outlet_distribute_id' => $outletdistributeId,
                    'variant_id' => $variation->id,
                    'quantity' => $value,
                    'purchased_price' => $fromOutletItemData->purchased_price,
                    'subtotal' => $fromOutletItemData->purchased_price * $value,
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success";

                CounterVariant::create([
                    'counter_id' => $request->to_counter,
                    'variant_id' => $variation->id,
                    'quantity' => $value,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success for countervariant";
                
                OutletStockHistory::create([
                    'outlet_id' => $request->from_outlet,
                    'machine_id' => $request->to_counter,
                    'quantity' => $value,
                    'variant_id' => $variation->id,
                    'branch' => IS_STORE,
                    'date' => now(),
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success for outletstockhistory";

                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['type'] = ISSUE_TYPE;
                $input['quantity'] = $value;
                $input['item_code'] = $key;
                $input['branch'] = $request->to_counter;
                $input['date'] = $request->date;
                $input['remark'] = $request->remark;
                $input['created_by'] = Auth::user()->id;
                $outletdistribute = OutletlevelHistory::create($input);
                // return "success for outletlevelhistory";

                //we need to add outletleveloverview and outletstockoverview
                
                $outlet_inv_qty = outlet_item_data($request->from_outlet, $variation->id); 
                $qty = $outlet_inv_qty->quantity - $value;
                // return $qty;

                $input = [];
                $input['quantity'] = $qty;
                $outlet_inv_qty->update($input);
            }   
            return redirect()->back()->with('success', 'Receive created successfully');

        } else {
            // return "machine";
            foreach($item_arr as $key => $value){
                $variation = Variation::where('item_code', $key)->first();
                $fromOutletItemData = outlet_item_data($request->from_outlet,$variation->id);
                // return $row;

                OutletDistributeProduct::create([
                    'outlet_distribute_id' => $outletdistributeId,
                    'variant_id' => $variation->id,
                    'quantity' => $value,
                    'purchased_price' => $fromOutletItemData->purchased_price,
                    'subtotal' => $fromOutletItemData->purchased_price * $value,
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success outletdistributeproduct";

                MachineVariant::create([
                    'machine_id' => $request->to_machine,
                    'variant_id' => $variation->id,
                    'quantity' => $value,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success machinevaritant";

                OutletStockHistory::create([
                    'outlet_id' => $request->from_outlet,
                    'machine_id' => $request->to_machine,
                    'quantity' => $value,
                    'variant_id' => $variation->id,
                    'branch' => IS_STORE,
                    'date' => now(),
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success outletstockhistory";

                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['type'] = ISSUE_TYPE;
                $input['quantity'] = $value;
                $input['item_code'] = $key;
                $input['branch'] = $request->to_machine;
                $input['date'] = $request->date;
                $input['remark'] = $request->remark;
                $input['created_by'] = Auth::user()->id;
                $outletdistribute = OutletlevelHistory::create($input);
                // return "success for outletlevelhistory";
                
                // from outlet for outletleveloverview start
                    $month = date('m', strtotime($request->date));
                    $year = date('Y', strtotime($request->date));
                    $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
                    ->where('outlet_id', $request->from_outlet)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->where('item_code',$key)->first();

                    if($outletleveloverview){     
                        $input = [];
                        $input['issued_qty'] = $outletleveloverview->issued_qty + $value;
                        $input['balance'] = ($outletleveloverview->opening_qty + $outletleveloverview->receive_qty) - $input['issued_qty'];
                        $input['updated_by'] = Auth::user()->id;
                        $outletleveloverview->update($input);
                    }else {
                        $input = [];
                        $input['date'] = $request->date;
                        $input['outlet_id'] = $request->from_outlet;
                        $input['item_code'] = $key;
                        $input['issued_qty'] = $value;
                        $input['balance'] = (0 + 0) - $value;
                        $input['created_by'] = Auth::user()->id;
                        OutletLevelOverview::create($input);
                    }
                // from outlet for outletleveloverview end
                
                //get main inventory qty  with variant_id and main outlet id
                // $outlet_inv_qty = OutletItem::select('outlet_item_data.quantity')
                //     ->join('outlet_item_data', 'outlet_item_data.outlet_item_id', '=', 'outlet_items.id')
                //     ->where('outlet_items.outlet_id', $request->from_outlet)
                //     ->where('outlet_items.variation_id', $variation->id)->first();
                //     // return $value;
                $outlet_inv_qty = outlet_item_data($request->from_outlet, $variation->id); 
                $qty = $outlet_inv_qty->quantity - $value;
                // return $qty;

                // $totalOutlet = OutletItemData::join('outlet_items', 'outlet_items.id', '=', 'outlet_item_data.outlet_item_id')
                // ->where('outlet_items.outlet_id', $request->from_outlet)
                // ->where('outlet_items.variation_id', $variation->id)->first();
                    // return $totalOutlet;

                $input = [];
                $input['quantity'] = $qty;
                $outlet_inv_qty->update($input);
                // return "success outletitemdata";

                $month = date('m', strtotime($request->date));
                $year = date('Y', strtotime($request->date));
                $variant = Variation::find($variation->id);
                $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')
                ->where('outlet_id', $request->from_outlet)
                ->where('machine_id', $request->to_machine)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('item_code',$variant->item_code)->first();
                
                if($outletstockoverview){     
                    $input = [];
                    $input['receive_qty'] = $outletstockoverview->receive_qty + $value;
                    $input['balance'] = ($outletstockoverview->opening_qty + $input['receive_qty']) - $outletstockoverview->issued_qty;
                    $input['updated_by'] = Auth::user()->id;
                    $outletstockoverview->update($input);
                }else {
                    $input = [];
                    $input['date'] = $request->date;
                    $input['outlet_id'] = $request->from_outlet;
                    $input['machine_id'] = $request->to_machine;
                    $input['item_code'] = $variant->item_code;
                    $input['receive_qty'] = $value;
                    $input['balance'] = $input['receive_qty'];
                    $input['created_by'] = Auth::user()->id;
                    OutletStockOverview::create($input);
                }
            }
            
            return redirect()->back()->with('success', 'Receive created successfully');
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outletdistribute.index')],
              ['name' => 'Detail Recieve Products']
        ];

        $outletdistribute_arr = [];
        $outletdistribute = OutletDistribute::find($id);
        $outletDistributeProducts = OutletDistributeProduct::select('outlet_distribute_products.*', 'variations.item_code', 'variations.image', 'size_variants.value')
        ->join('variations', 'variations.id', '=', 'outlet_distribute_products.variant_id')
        ->join('size_variants', 'size_variants.id', '=', 'variations.size_variant_value')
        ->where('outlet_distribute_id', $id)->get();
        $outletdistribute_arr['outletdistribute'] = $outletdistribute;
        $outletdistribute_arr['outletDistributeProducts'] = $outletDistributeProducts;

        $outlets = getOutlets();
        $machines = getMachines();
        
        return view('outletdistribute.show', compact('outletdistribute_arr', 'outlets', 'machines', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$from_outlet)
    {
        // return $from_outlet;
        $outlets = getOutlets();
        $counter_machines = getMachinesWithOutletID($from_outlet);
        $counter = $counter_machines['counter'];
        $machines = $counter_machines['machine'];
        $outletdistributes = OutletDistribute::findorFail($id);
        $outlet_distribute_products = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name','variations.item_code')
        ->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
        ->join("products", "products.id", "=", "variations.product_id")
        ->where("outlet_distribute_id", $id)
        ->get();
        
        $outletitems = OutletItem::select('quantity', 'variation_id')->where('outlet_id', $from_outlet)->get();
        $variant_qty = [];
        foreach ($outletitems as $outletitem) {
            $variant_qty[$outletitem->variation_id] = $outletitem->quantity;
        }

        return view('outletdistribute.edit', compact('outletdistributes', 'outlets', 'counter', 'machines', 'outlet_distribute_products', 'variant_qty'));
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
        $outletdistribute = OutletDistribute::find($id);
        $outletdistribute->update($input);

        $outlet_distribute_products = OutletDistributeProduct::where('outlet_distribute_id', $id)->get();
        // return $outlet_distribute_products;

        //counterMachine 1 is counter / counterMachine 2 is machine
        if($request->counterMachine === "1") {
            foreach($outlet_distribute_products as $row){
            // return $row;
                $input = [];
                $input['counter_id'] = $request->toCounterMachine;
                $input['variant_id'] = $row->variant_id;
                $input['created_by'] = Auth::user()->id;

                //create $input with outlet_itmes_tbl columns
                $countervariant = CounterVariant::select('quantity')
                ->where('counter_id', $request->toCounterMachine)
                ->where('variant_id', $row->variant_id)
                ->first();
                if($countervariant) {
                    $input['quantity'] = $row->quantity + $countervariant->quantity;
                    $countervariant->update($input);
                }else {
                    $input['quantity'] = $row->quantity;
                    CounterVariant::create($input);
                }
                
                //get main inventory qty  with variant_id and main outlet id
            
                $outlet_inv_qty = OutletItem::select('quantity')
                    ->where('outlet_id', $request->from_outlet)
                    ->where('variation_id', $row->variant_id)->first();
                
                $qty = $outlet_inv_qty->quantity - $row->quantity;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = OutletItem::where('outlet_id', $request->from_outlet)->where('variation_id', $row->variant_id)->first();
                $input = [];
                $input['quantity'] = $qty;
                $totalOutlet->update($input);
                
                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['machine_id'] = $request->toCounterMachine;
                $input['quantity'] = $row->quantity;
                $input['variant_id'] = $row->variant_id;
                $input['branch'] = IS_STORE;
                $input['date'] = now();
                $input['remark'] = $row->remark;
                $input['created_by'] = Auth::user()->id;
                OutletStockHistory::create($input);

                // receive-qty = 12;
                // item_code(name) = variant_id (id)

                // item_code(name) variation(name,id) variant_id(1)
                // $outletstockoverview = OutletStockOverview::join('variations', 'variations.item_code', '=', 'outlet_stock_overviews.item_code')
                //                         // ->where('variations.id', $row->variant_id)
                //                         ->get();
                // return $outletstockoverview;

                // $input = [];
                // $input['quantity'] = $row->quantity;
                // $outletstockoverview->update($input);
            }   
            return redirect()->route('outletdistribute.create', ['id' => $outletdistribute->from_outlet])
                ->with('success','Distribute updated successfully');
        } else {
            foreach($outlet_distribute_products as $row){
            // return $row;
                $input = [];
                $input['machine_id'] = $request->toCounterMachine;
                $input['variant_id'] = $row->variant_id;
                $input['quantity'] = $row->quantity;
                $input['created_by'] = Auth::user()->id;

                //create $input with outlet_itmes_tbl columns
                $machinevariant = MachineVariant::select('quantity')->where('machine_id', $request->toCounterMachine)->where('variant_id', $row->variant_id)->first();
                if($machinevariant) {
                    $input['quantity'] = $row->quantity + $machinevariant->quantity;
                    $machinevariant->update($input);
                }else {
                    $input['quantity'] = $row->quantity;
                    MachineVariant::create($input);
                }
                
                //get main inventory qty  with variant_id and main outlet id
                $outlet_inv_qty = OutletItem::select('quantity')
                    ->where('outlet_id', $request->from_outlet)
                    ->where('variation_id', $row->variant_id)->first();
                
                $qty = $outlet_inv_qty->quantity - $row->quantity;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = OutletItem::where('outlet_id', $request->from_outlet)->where('variation_id', $row->variant_id)->first();
                $input = [];
                $input['quantity'] = $qty;
                $totalOutlet->update($input);
                
                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['machine_id'] = $request->toCounterMachine;
                $input['quantity'] = $row->quantity;
                $input['variant_id'] = $row->variant_id;
                $input['branch'] = IS_STORE;
                $input['date'] = now();
                $input['remark'] = $row->remark;
                $input['created_by'] = Auth::user()->id;
                OutletStockHistory::create($input);

                $month = date('m', strtotime($request->date));
                $year = date('Y', strtotime($request->date));
                $variant = Variation::find($row->variant_id);
                $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')
                ->where('outlet_id', $request->from_outlet)
                ->where('machine_id', $request->toCounterMachine)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('item_code',$variant->item_code)->first();
                
                if($outletstockoverview){     
                    $input = [];
                    $input['receive_qty'] = $outletstockoverview->receive_qty + $row->quantity;
                    $input['balance'] = ($outletstockoverview->opening_qty + $input['receive_qty']) - $outletstockoverview->issued_qty;
                    $input['updated_by'] = Auth::user()->id;
                    $outletstockoverview->update($input);
                }
            }
            
            return redirect()->route('outletdistribute.create', ['id' => $outletdistribute->from_outlet])
                ->with('success','Distribute updated successfully');
        }
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
}
