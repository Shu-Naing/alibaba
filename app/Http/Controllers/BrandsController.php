<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;
use Auth;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brands::where('status', 1)->get();

        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'brand_name' => 'required|unique:brands',
            'note' => 'required'
        ]);
        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        Brands::create($inputs);
        
        return redirect()->route('brands.index')->with('succes','Brands create successfully');
    }
    public function edit($id)
    {
        $brands = Brands::find($id);

        return view('brands.edit',compact('brands'));
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'brand_name' => 'required',
            'note' => 'required',
        ]);

        $input = $request->all();    
        $inputs['updated_by'] = Auth::user()->id;
        $brand = Brands::find($id);
        $brand->update($input);
    
        // return redirect()->route('distributors.edit',compact('distributor'))
        //                 ->with('success','Distributor updated successfully');

        return redirect()->route('brands.index')
            ->with('success','Brand updated successfully');
    }
    public function destroy($id)
    {    
        $brands = Brands::find($id);
        $brands->updated_by = $id = Auth::id();
        $brands->status = 0;
        $brands->save();

        return redirect()->route('brands.index')
                        ->with('success','Brands deleted successfully');
    }
}
