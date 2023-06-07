<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variantion;
use App\Models\DistributeProducts;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'List Outlets']
        ];
        return view('products.name', compact('breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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

    public function get_product_lists(){
        $product = Variants::select("variants.id", "products.product_name")->join("products", "variants.product_id", "=", "products.id")->get();

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->product_name;           
        }
        return $product_arr;
    }

    public function update_product_qty(Request $request, $id) {
        
        $DistributeProducts = DistributeProducts::find($id); 

        $input = [];
        $input['quantity'] = $request->qty;
        $input['subtotal'] = $request->qty * $DistributeProducts->purchased_price;

        return $DistributeProducts->update($input);
    }

     public function delete_dis_product($id) {
        // return $id;
        $DistributeProducts = DistributeProducts::find($id); 
        if ($DistributeProducts) {
            $result = $DistributeProducts->delete();
            return $result;
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $result = $DistributeProducts->delete();
    }

}
