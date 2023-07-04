<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlets;
use App\Models\distributes;
use App\Models\DistributeProducts;
use App\Models\Variation;
use App\Models\OutletItem;
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
        $breadcrumbs = [
                ['name' => 'Reports', 'url' => route('distribute.index')],
                ['name' => 'List Distribute Product']
        ];
        $outlets = getOutlets();

        $distributes = DistributeProducts::join('distributes','distributes.id','=','distribute_products.distribute_id')->get();
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
            'reference_No' => 'required|unique:distributes',
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
        return redirect()->route('distribute.edit', ['id' => $distributeId, 'from_outlet'=>$distribute->from_outlet]);
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
        $outlets = getOutlets();
        $distribute = distributes::findorFail($id);
        $distribute_products = DistributeProducts::select("distribute_products.*", "products.product_name", "variations.item_code")->join("variations", "variations.id", "=", "distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("distribute_id", $id)->get();

        $outletitems = OutletItem::select('quantity', 'variation_id')->where('outlet_id', $from_outlet)->get();
        $variant_qty = [];
        foreach ($outletitems as $outletitem) {
            $variant_qty[$outletitem->variation_id] = $outletitem->quantity;
        }
        return view('distribute.edit', compact('distribute','outlets', 'distribute_products', 'variant_qty'));
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
}
