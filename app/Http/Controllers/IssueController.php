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
        // return "hello";
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
        return view('issue.create', compact('outlets', 'id', 'machines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->store_customer;
        $this->validate($request,[ 
            'date' => 'required',
            'reference_No' => 'required|unique:outlet_distributes',
            'status' => 'required',             
            'to_machine' => 'required',
            'store_customer' => 'required'
        ]);
        // return $request->store_customer;
        $input = $request->all();
        $input['type'] = ISSUE_TYPE;
        $input['created_by'] = Auth::user()->id;
        $outletdistribute = OutletDistribute::create($input);
        return redirect()->route('issue.edit', ['id' => $outletdistribute->id,'from_outlet'=>$outletdistribute->from_outlet]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$from_outlet)
    {
        $outlets = getOutlets();
        $machines = getIssuedMachinesWithOutletID($from_outlet);
        $outletdistributes = OutletDistribute::findorFail($id);
        $outlet_distribute_products = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $id)->get();

        $outletitems = OutletItem::select('quantity', 'variation_id')->where('outlet_id', $from_outlet)->get();
        $variant_qty = [];
        foreach ($outletitems as $outletitem) {
            $variant_qty[$outletitem->variation_id] = $outletitem->quantity;
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

                //update issued qty in outletstock overview tbl



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

                $variant = Variation::find($row->variant_id);
                $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')->where('item_code',$variant->item_code)->first();
                $input = [];
                $input['issued_qty'] = $outletstockoverview->issued_qty + $row->quantity;
                $input['balance'] = ($outletstockoverview->opening_qty + $outletstockoverview->receive_qty) - $outletstockoverview['issued_qty'];
                $input['updated_by'] = Auth::user()->id; 

                if($outletstockoverview){                                       
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
