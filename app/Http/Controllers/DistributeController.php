<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Outlets;
use App\models\distributes;
use App\models\DistributeProducts;
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
        $distribute_products = DistributeProducts::select("distribute_products.*", "products.product_name")->join("variants", "variants.id", "=", "distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variants.product_id")->where("distribute_id", $id)->get();
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
        $brand = distributes::find($id);
        $brand->update($input);
        return redirect()->back()
            ->with('success','Brand updated successfully');
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
