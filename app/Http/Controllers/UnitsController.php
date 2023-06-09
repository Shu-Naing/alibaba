<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Units;
use Auth;

class UnitsController extends Controller
{
    public function index()
    {
        $units = Units::where('status', 1)->get();

        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'short_name' => 'required',
            'allow_decimal' => ['nullable', 'regex:/^\d{1,9}(\.\d{1,2})?$/'],
        ]);

        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        units::create($inputs);

        return redirect()->route('units.index')->with('success', 'Units created successfully');
    }

    public function edit($id)
    {
        $units = units::find($id);

        return view('units.edit',compact('units'));
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'short_name' => 'required',
            'allow_decimal' => ['nullable', 'regex:/^\d{1,9}(\.\d{1,2})?$/'],
        ]);

        $input = $request->all();    
        $inputs['updated_by'] = Auth::user()->id;
        $brand = units::find($id);
        $brand->update($input);
    
        // return redirect()->route('distributors.edit',compact('distributor'))
        //                 ->with('success','Distributor updated successfully');

        return redirect()->route('units.index')
            ->with('success','Brand updated successfully');
    }
    public function destroy($id)
    {    
        $units = units::find($id);
        $units->updated_by = $id = Auth::id();
        $units->status = 0;
        $units->save();

        return redirect()->route('units.index')
                        ->with('success','units deleted successfully');
    }
}
