<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::where('status', 1)->get();

        return view('categories.index', ['categories' => $categories]);

    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories',
        ]);

        $category = Categories::create([
            'category_name' => $request->category_name,
            'category_code' => $request->category_code,
            'description' => $request->description,
            'create_by' => auth()->id()
        ]);

        return redirect()->route('categories.index')
            ->with('success','Category created successfully.');
    }

    public function edit($id)
    {
        $cate = Categories::find($id);

        return view('categories.edit',compact('cate'));
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

    public function destroy($id)
    {    
        $category = Categories::find($id);
        $category->updated_by = $id = Auth::id();
        $category->status = 0;
        $category->save();

        return redirect()->route('categories.index')
            ->with('success','Category deleted successfully');
    }
}
