<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Outlets;
use App\Models\Categories;
use App\Models\Counter;
use App\Models\DistributeProducts;
use App\Models\OutletDistributeProduct;
use Illuminate\Support\Facades\DB;

class OutletController extends Controller
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
        // $outlets = Outlets::join('categories', 'categories.outlet_id', '=', 'outlets.id')
        //     ->select('outlets.id', 'outlets.name', 'outlets.city', 'outlets.state', 'categories.category_name')
        //     ->get();
        $outlets = Outlets::where('id','>',1)->get();
        // $outlets = Outlets::with('categories')->first();
        return view('outlets.index', compact('breadcrumbs', 'outlets'));
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
              ['name' => 'Create']
        ];
        return view('outlets.create', compact('breadcrumbs'));
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
            'outlet_id' => 'required|unique:outlets',
            'name' => 'required',
            'city' => 'required',
            'state' => 'required',
            
        ]);

        // return $request;

        // $outlet = new Outlets();
        // $outlet->outlet_id = $request->outlet_id;
        // $outlet->name = $request->name;
        // $outlet->city = $request->city;
        // $outlet->state = $request->state;
        // $outlet->country = $request->country;
        // $outlet->created_by = Auth::id();
        // $outlet->update_by = Auth::id();
        // $outlet->save();
        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        $outletofid = Outlets::create($inputs);
        // return $outletofid;
        // return $outletofid->outlet_id;

        $input = [];
        $input['outlet_id'] = $outletofid->id;
        $input['name'] = $outletofid->name.'_counter';
        $input['created_by'] = Auth::user()->id;
        Counter::create($input);

        return redirect()->back()->with('success', 'Outlet Form Data Has Been inserted');
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
        $outlet = Outlets::findorFail($id);
        $breadcrumbs = [
            ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'Edit', 'url' => route('outlets.edit', $outlet->id)]
        ];

        $dummyDataPath = public_path('/dummy_data.json');
        $dummyData = json_decode(file_get_contents($dummyDataPath));

        return view('outlets.edit', compact('breadcrumbs', 'outlet', 'dummyData'));
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
        $this->validate($request, [
            'outletId' => 'required',
            'name' => 'required',
            'city' => 'required',
            'state' => 'required',
            
        ]);

        $outlet = Outlets::findorFail($id);
        $outlet->outlet_id = $request->outletId;
        $outlet->name = $request->name;
        $outlet->city = $request->city;
        $outlet->state = $request->state;
        // $outlet->country = $request->country;
        // $outlet->created_by = Auth::id();
        $outlet->updated_by = Auth::id();
        $outlet->save();

        if ($outlet->save()) {
            // Successful save, redirect to a success page or return a success response
            return redirect('outlets')->with('success', 'Outlet Form Data Has Been updated');
        } else {
            // Failed to save, redirect back to the form view with input and errors
            return redirect('outlets')->with('error', 'Failed to update the record.');
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
        $outlet = Outlets::findOrFail($id);
        // return $outlet;

        // Toggle the status
        $outlet->status = ($outlet->status == 1) ? 0 : 1;
        $outlet->save();

        if ($outlet->save()) {
            // Successful save, redirect to a success page or return a success response
            return redirect()->back()->with('success', 'Outlet Form Data Has Been updated');
        } else {
            // Failed to save, redirect back to the form view with input and errors
            return redirect()->back()->with('error', 'Failed to update the record.');
        }
        
    }
    
    public function history(Request $request)
    {
        $breadcrumbs = [
              ['name' => 'Outlets', 'url' => route('outlets.index')],
              ['name' => 'Outlet History']
        ];
        // return "hello".$request->outlet;
        $outlets = getOutlets();
        $machines = getMachines();
        // return view('outlets.history', compact('outlets'));
        if($request->outlet){
            $id = $request->outlet;
        }else{
            $id = MAINOUTLETID;
        }

        $issued_distribute_products = DistributeProducts::join('distributes', 'distributes.id', 'distribute_products.distribute_id')->join('variations', 'variations.id', 'distribute_products.variant_id')->where('from_outlet', $id)->get();
        // return $issued_distribute_porducts;

        $recieved_distribute_products = DistributeProducts::join('distributes', 'distributes.id', 'distribute_products.distribute_id')->join('variations', 'variations.id', 'distribute_products.variant_id')->where('to_outlet', $id)->get();

        // $recieved_outlet_distribute_products = OutletDistributeProduct::join('outlet_distributes', 'outlet_distributes.id', 'outlet_distribute_products.outlet_distribute_id')->join('variations', 'variations.id', 'outlet_distribute_products.variant_id')->where('from_outlet', $id)->where('store_customers', IS_STORE )->orWhere('store_customer', null)->get();
        $recieved_outlet_distribute_products = OutletDistributeProduct::join('outlet_distributes', 'outlet_distributes.id', 'outlet_distribute_products.outlet_distribute_id')
        ->join('variations', 'variations.id', 'outlet_distribute_products.variant_id')
        ->where('from_outlet', $id)
        ->where(function ($query) {
                $query->where('store_customer', 2)
                    ->orWhereNull('store_customer');
        })
        ->get();

        $data = [];
        $data['issue'] = $issued_distribute_products;
        $data['recieve'] = $recieved_distribute_products;
        $data['outletrecieve'] = $recieved_outlet_distribute_products;
        // $di
        return view('outlets.history', compact('outlets', 'data', 'breadcrumbs', 'machines'));
        
    }

}
