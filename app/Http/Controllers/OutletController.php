<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Outlets;

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
              ['name' => 'Outlets List', 'url' => route('outlets.index')]
        ];
        return view('outlets.index', compact('breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Outlets Create', 'url' => route('outlets.create')]
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
}
