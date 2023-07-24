<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutletDistribute;
use App\Models\OutletDistributeProduct;
use App\Models\MachineVariant;
use App\Models\OutletStockHistory;
use App\Models\Machines;
use App\Models\OutletItem;
use App\Models\Variation;
use App\Models\OutletStockOverview;
use App\Models\OutletlevelHistory;
use App\Models\OutletLevelOverview;
use Auth;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outletdistribute.index')],
              ['name' => 'List Issue Distributes Product']
        ];
        $outlets = getOutlets();
        $machines = getMachines();
        $outletDistributes = OutletDistribute::all()->where('type', ISSUE_TYPE);
        return view('issue.index', compact('outletDistributes', 'breadcrumbs', 'outlets', 'machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $outlets = getOutlets();
        $machines = getIssuedMachinesWithOutletID($id);

        $latestRef = OutletDistribute::orderBy('created_at', 'desc')->value('reference_No');
        $generatedRef = refGenerateCode($latestRef);

        return view('issue.create', compact('outlets', 'id', 'machines', 'generatedRef'));
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
            'status' => 'required',        
            'to_machine' => 'required',
            'store_customer' => 'required'
        ]);
        // return $request->store_customer;
        $input = $request->only('date','reference_No', 'statusx','from_outlet', 'to_machine', 'store_customer', 'remark');
        $input['type'] = ISSUE_TYPE;
        $input['created_by'] = Auth::user()->id;
        $outletdistribute = OutletDistribute::create($input);
        $outletdistributeId = $outletdistribute->id;
        // return $outletdistributeId;

        foreach($data as $key => $value) {
            $key_arr = explode("_", $key);
            if(count($key_arr) > 1){
                if($key_arr[1] == 'qtyNumber'){ 
                    $item_arr[$key_arr[0]] = $value;    
                }
            }
        }

        if($request->store_customer === IS_CUSTOMER) {
            // return "ehllo";
            foreach($item_arr as $key => $value){
                $variation = Variation::where('item_code', $key)->first();

                $outlet_inv_qty = MachineVariant::select('quantity')
                    ->where('machine_id', $request->to_machine)
                    ->where('variant_id', $variation->id)->first();

                $qty = $outlet_inv_qty->quantity - $value;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = MachineVariant::where('machine_id', $request->to_machine)->where('variant_id', $variation->id)->first();
                $input = [];
                $input['quantity'] = $qty;
                $totalOutlet->update($input);
                // return "hello";
                
                OutletStockHistory::create([
                    'outlet_id' => $request->from_outlet,
                    'machine_id' => $request->to_machine,
                    'type' => ISSUE_TYPE,
                    'quantity' => $value,
                    'variant_id' => $variation->id,
                    'branch' => $request->store_customer,
                    'date' => now(),
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success outletstockhistory";
            }

            return redirect()->back();
        } else {
            foreach($item_arr as $key => $value){
                $variation = Variation::where('item_code', $key)->first();
                $fromOutletItemData = outlet_item_data($request->from_outlet,$variation->id);

                $machine_variant_qty = MachineVariant::select('quantity')
                    ->where('machine_id', $request->to_machine)
                    ->where('variant_id', $variation->id)->first();

                $machineqty = $machine_variant_qty->quantity - $value;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = MachineVariant::where('machine_id', $request->to_machine)->where('variant_id', $variation->id)->first();
                $input = [];
                $input['quantity'] = $machineqty;
                $totalOutlet->update($input);
                // return "hello";

                $outlet_inv_qty = outlet_item_data($request->from_outlet, $variation->id); 
                $qty = $outlet_inv_qty->quantity + $value;
                $input = [];
                $input['quantity'] = $qty;
                $outlet_inv_qty->update($input);
                // return "success outletitemdata";
                
                OutletStockHistory::create([
                    'outlet_id' => $request->from_outlet,
                    'machine_id' => $request->to_machine,
                    'type' => ISSUE_TYPE,
                    'quantity' => $value,
                    'variant_id' => $variation->id,
                    'branch' => $request->store_customer,
                    'date' => now(),
                    'remark' => $request->remark,
                    'created_by' => Auth::user()->id,
                ]);
                // return "success outletstockhistory";
                
                $outletdistribute = OutletlevelHistory::create([
                   'outlet_id' => $request->from_outlet,
                   'type' => RECIEVE_TYPE,
                   'quantity' => $value,
                   'item_code' => $key,
                   'branch' => $request->to_machine,
                   'date' => $request->date,
                   'remark' => $request->remark,
                   'created_by' => Auth::user()->id,
                ]);
                // return "success outlevelhistory";

                // from outlet for outletleveloverview start
                    $month = date('m', strtotime($request->date));
                    $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
                    ->where('outlet_id', $request->from_outlet)
                    ->whereMonth('date', $month)
                    ->where('item_code',$key)->first();

                    if($outletleveloverview){     
                        $input = [];
                        $input['receive_qty'] = $outletleveloverview->receive_qty + $value;
                        $input['balance'] = ($outletleveloverview->opening_qty + $input['receive_qty']) - $outletleveloverview->issued_qty;
                        $input['updated_by'] = Auth::user()->id;
                        $outletleveloverview->update($input);
                    }else {
                        $input = [];
                        $input['date'] = $request->date;
                        $input['outlet_id'] = $request->from_outlet;
                        $input['item_code'] = $key;
                        $input['receive_qty'] = $value;
                        $input['balance'] = $value;
                        $input['created_by'] = Auth::user()->id;
                        OutletLevelOverview::create($input);
                    }
                // from outlet for outletleveloverview end
            }
            return redirect()->back();
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
              ['name' => 'Detail Issue Products']
        ];

        $outletdistribute_arr = [];
        $outletdistribute = OutletDistribute::find($id);
        $outletDistributeProducts = OutletDistributeProduct::select('outlet_distribute_products.*', 'variations.item_code', 'variations.image', 'variations.value')
        ->join('variations', 'variations.id', '=', 'outlet_distribute_products.variant_id')
        ->where('outlet_distribute_id', $id)->get();
        $outletdistribute_arr['outletdistribute'] = $outletdistribute;
        $outletdistribute_arr['outletDistributeProducts'] = $outletDistributeProducts;

        $outlets = getOutlets();
        $machines = getMachines();
        
        return view('issue.show', compact('outletdistribute_arr', 'outlets', 'machines', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$from_outlet,$to_machine)
    {
        $outlets = getOutlets();
        $machines = getIssuedMachinesWithOutletID($from_outlet);
        $outletdistributes = OutletDistribute::findorFail($id);
        $outlet_distribute_products = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $id)->get();
        //check variant quantity with from machine id 
        $machine_variants = MachineVariant::select('quantity', 'variant_id')->where('machine_id', $to_machine)->get();
        $variant_qty = [];
        foreach ($machine_variants as $machinevariant) {
            $variant_qty[$machinevariant->variant_id] = $machinevariant->quantity;
        }        
        return view('issue.edit', compact('outletdistributes', 'outlets', 'machines', 'outlet_distribute_products', 'variant_qty'));
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
        
        // return $request->store_customer;
        if($request->store_customer === "1") {
            foreach($outlet_distribute_products as $row){
            //     $input = [];
            //     $input['outlet_id'] = $request->from_outlet;
            //     $input['variation_id'] = $row->variant_id;
            //     $input['quantity'] = $row->quantity;
            //     $input['created_by'] = Auth::user()->id;

            //     //create $input with outlet_itmes_tbl columns
            //     OutletItem::create($input);
            
                $outlet_inv_qty = MachineVariant::select('quantity')
                    ->where('machine_id', $request->to_machine)
                    ->where('variant_id', $row->variant_id)->first();
                
                $qty = $outlet_inv_qty->quantity - $row->quantity;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = MachineVariant::where('machine_id', $request->to_machine)->where('variant_id', $row->variant_id)->first();
                $input = [];
                $input['quantity'] = $qty;
                $totalOutlet->update($input);
                
                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['machine_id'] = $request->to_machine;
                $input['type'] = ISSUE_TYPE;
                $input['quantity'] = $row->quantity;
                $input['variant_id'] = $row->variant_id;
                $input['branch'] = $request->store_customer;
                $input['date'] = now();
                $input['remark'] = $row->remark;
                $input['created_by'] = Auth::user()->id;
                OutletStockHistory::create($input);
            }
            return redirect()->route('issue.create', ['id' => $outletdistribute->from_outlet])
                ->with('success','issue updated successfully');
        } else {
            foreach($outlet_distribute_products as $row){
            //     $input = [];
            //     $input['outlet_id'] = $request->from_outlet;
            //     $input['variation_id'] = $row->variant_id;
            //     $input['quantity'] = $row->quantity;
            //     $input['created_by'] = Auth::user()->id;

            //     //create $input with outlet_itmes_tbl columns
            //     OutletItem::create($input);
            // return $request->to_machine;
                $machine_inv_qty = MachineVariant::select('*')
                        ->where('machine_id', $request->to_machine)
                        ->where('variant_id', $row->variant_id) 
                    ->first();
                // return $machine_inv_qty."hello";
                $machineqty = $machine_inv_qty->quantity - $row->quantity;
            
                $outlet_inv_qty = OutletItem::select('quantity')
                    ->where('outlet_id', $request->from_outlet)
                    ->where('variation_id', $row->variant_id)->first();
                
                $outletqty = $outlet_inv_qty->quantity + $row->quantity;
                
                //update outlet_items_tbl with main outlet id 
                $totalOutlet = MachineVariant::where('machine_id', $request->to_machine)->where('variant_id', $row->variant_id)->first();
                $input = [];
                $input['quantity'] = $machineqty;
                $totalOutlet->update($input);

                $totalOutlet = OutletItem::where('outlet_id', $request->from_outlet)->where('variation_id', $row->variant_id)->first();
                $input = [];
                $input['quantity'] = $outletqty;
                $totalOutlet->update($input);
                
                $input = [];
                $input['outlet_id'] = $request->from_outlet;
                $input['machine_id'] = $request->to_machine;
                $input['type'] = ISSUE_TYPE;
                $input['quantity'] = $row->quantity;
                $input['variant_id'] = $row->variant_id;
                $input['branch'] = $request->store_customer;
                $input['date'] = now();
                $input['remark'] = $row->remark;
                $input['created_by'] = Auth::user()->id;
                OutletStockHistory::create($input);  
                
                $month = date('m', strtotime($request->date));
                $variant = Variation::find($row->variant_id);
                $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')
                ->where('outlet_id', $request->from_outlet)
                ->where('machine_id', $request->toCounterMachine)
                ->whereMonth('date', $month)
                ->where('item_code',$variant->item_code)
                ->first();
                if($outletstockoverview){                                       
                    $input = [];
                    $input['issued_qty'] = $outletstockoverview->issued_qty + $row->quantity;
                    $input['balance'] = ($outletstockoverview->opening_qty + $outletstockoverview->receive_qty) - $outletstockoverview['issued_qty'];
                    $input['updated_by'] = Auth::user()->id; 
                    $outletstockoverview->update($input);
                }
            }

            
            return redirect()->route('issue.create', ['id' => $outletdistribute->from_outlet])
                ->with('success','issue updated successfully');
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
