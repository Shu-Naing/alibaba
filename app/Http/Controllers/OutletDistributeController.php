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
              ['name' => 'Report', 'url' => route('outletdistribute.index')],
              ['name' => 'List Outlet Distributes']
        ];
        $outlets = getOutlets();
        $machines = getMachines();
        $outletDistributes = OutletDistribute::all();
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

        return view('outletdistribute.create', compact('outlets', 'id','counter', 'machines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //get to machin id 
        // $this->validate($request, [
        //     'date' => 'required',
        //     'reference_No' => 'required',
        //     'status' => 'required',
        //     'counter_machine' => ['required', 'in:1,2'],
        //     'to_counter' => ['sometimes', 'required', 'when:counter_machine,1'],
        //     'to_machine' => ['sometimes', 'required', 'when:counter_machine,2'],
        // ]);

        
        $this->validate($request,[ 
            'date' => 'required',
            'reference_No' => 'required|unique:outlet_distributes',
            'status' => 'required',             
            'counterMachine' => 'required|in:1,2',
            'to_counter' => 'sometimes|required|required_if:counterMachine,1',
            'to_machine' => 'sometimes|required|required_if:counterMachine,2',
        ]);
        
        $input = $request->only('date','reference_no','status','from_outlet','reference_No');
        if($request->counterMachine == 1){
            $input['to_machine'] = $request->to_counter;
        }else{
            $input['to_machine'] = $request->to_machine;
        }
        $input['counter_machine'] = $request->counterMachine;
        $input['created_by'] = Auth::user()->id;

        $outletdistribute = OutletDistribute::create($input);

        // return $outletdistribute;
       
        return redirect()->route('outletdistribute.edit', ['id' => $outletdistribute->id,'from_outlet'=>$outletdistribute->from_outlet]);
       
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
              ['name' => 'Report', 'url' => route('outletdistribute.index')],
              ['name' => 'Detail Outlet Distribute Products']
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
                $input['branch'] = $row->quantity;
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
                $input['branch'] = $row->quantity;
                $input['date'] = now();
                $input['remark'] = $row->remark;
                $input['created_by'] = Auth::user()->id;
                OutletStockHistory::create($input);

                $variant = Variation::find($row->variant_id);
                $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')->where('item_code',$variant->item_code)->first();
                
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
