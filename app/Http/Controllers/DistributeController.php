<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Outlets;
use App\models\distributes;
use App\models\DistributeProducts;
use App\models\Variation;
use App\models\OutletItem;
use Validator;
use Auth;

class DistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "hello";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'Distribute Products']
        ];
        $outlets = getOutlets();
        // return $outlets;
        return view('distribute.create', compact('breadcrumbs', 'outlets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'reference_No' => 'required',
            'status' => 'required',
            'from_outlet' =>'required',
            'to_outlet' =>'required',
        ]);
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;

        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'Distribute Products']
        ];
        // $outlets = getOutlets();

        $distribute = distributes::create($input);
        $distributeId = $distribute->id;
        
        // return redirect('distribute.edit')->view(, compact('distribute','outlets'));
        return redirect()->route('distribute.edit', ['distribute' => $distributeId]);
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
    public function edit($id)
    {
        $outlets = getOutlets();
        $distribute = distributes::findorFail($id);
        $distribute_products = DistributeProducts::select("distribute_products.*", "products.product_name")->join("variations", "variations.id", "=", "distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("distribute_id", $id)->get();
        return view('distribute.edit', compact('distribute','outlets', 'distribute_products'));
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
<<<<<<< HEAD
        
        
=======
>>>>>>> 3b2807618f725cfb6178a09dd5b5d4256125f4d4

        //select distribute product with distribute id
        // $distribute_products = array();
        $distribute_products = DistributeProducts::where('distribute_id', $id)->get();
        // return $distribute_products;
        foreach($distribute_products as $row){
            $input = [];
<<<<<<< HEAD
            $input['outlet_id'] = $row->outlet_id;
            $input['variation_id'] = $row->variant_id;
            $input['quantity'] = $row->quantity;
            //create $input with outlet_itmes_tbl columns
            OutletItemsModel::create($input);
            //get main inventory qty  with variant_id and main outlet id
            $main_inv_qty = OutletItem::select('quantity')->where('outlet_id', MAIN_INV_ID)->where('variant_id', $row->variant_id)->first();
            $qty = $main_inv_qty->quantity - $row->quantity;
            
            //update outlet_items_tbl with main outlet id 
            $mainOutlet = OutletItem::where('outlet_id', MAIN_INV_ID)->where('variant_id', $row->variant_id)->first();
=======
            $input['outlet_id'] = $request->to_outlet;
            $input['variation_id'] = $row->variant_id;
            $input['quantity'] = $row->quantity;
            $input['created_by'] = Auth::user()->id;

            //create $input with outlet_itmes_tbl columns
            OutletItem::create($input);
            //get main inventory qty  with variant_id and main outlet id
            $main_inv_qty = OutletItem::select('quantity')->where('outlet_id', MAIN_INV_ID)->where('variation_id', $row->variant_id)->first();
            $qty = $main_inv_qty->quantity - $row->quantity;
            
            //update outlet_items_tbl with main outlet id 
            $mainOutlet = OutletItem::where('outlet_id', MAIN_INV_ID)->where('variation_id', $row->variant_id)->first();
>>>>>>> 3b2807618f725cfb6178a09dd5b5d4256125f4d4
            $input = [];
            $input['quantity'] = $qty;
            $mainOutlet->update($input);  

        }
        return redirect()->back()
            ->with('success','Distribute updated successfully');
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
