<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brands;
use Auth;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brands::all();

        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'brand_name' => 'required',
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
    public function update(Request $request, Categories $category)
    {
        $request->validate([
            'category_code' => 'required|unique:categories,category_code,'.$category->id,
            'category_name' => 'required',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success','Category updated successfully');
    }
}
