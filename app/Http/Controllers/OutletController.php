<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Outlets;
use App\Models\Categories;
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
        $outlets = Outlets::all();
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
              ['name' => 'Create Outlets']
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
            'outletId' => 'required',
            'name' => 'required'
        ]);

        // return $request->name;

        $outlet = new Outlets();
        $outlet->outlet_id = $request->outletId;
        $outlet->name = $request->name;
        $outlet->city = $request->city;
        $outlet->state = $request->state;
        $outlet->country = $request->country;
        $outlet->create_by = Auth::id();
        $outlet->update_by = Auth::id();
        $outlet->save();

        if ($outlet->save()) {
            // Successful save, redirect to a success page or return a success response
            return redirect()->back()->with('success', 'Outlet Form Data Has Been inserted');
        } else {
            // Failed to save, redirect back to the form view with input and errors
            return redirect()->back()->with('error', 'Failed to save the record.');
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
              ['name' => 'Outlets Edit', 'url' => route('outlets.edit', $outlet->id)]
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
            'name' => 'required'
        ]);

        $outlet = Outlets::findorFail($id);
        $outlet->outlet_id = $request->outletId;
        $outlet->name = $request->name;
        $outlet->city = $request->city;
        $outlet->state = $request->state;
        $outlet->country = $request->country;
        $outlet->create_by = Auth::id();
        $outlet->update_by = Auth::id();
        $outlet->save();

        if ($outlet->save()) {
            // Successful save, redirect to a success page or return a success response
            return redirect()->back()->with('success', 'Outlet Form Data Has Been updated');
        } else {
            // Failed to save, redirect back to the form view with input and errors
            return redirect()->back()->with('error', 'Failed to update the record.');
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

}
