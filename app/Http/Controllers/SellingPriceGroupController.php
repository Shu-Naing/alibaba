<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellingPriceGroup;
use Auth;

class SellingPriceGroupController extends Controller
{
    public function index()
    {

        $sellingprice = SellingPriceGroup::whereIn('status', [1, 2])->get();

        return view('sellingprice.index', compact('sellingprice'));
    }

    public function create()
    {
        return view('sellingprice.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'descriptions' => 'required'
        ]);
        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        SellingPriceGroup::create($inputs);
        
        return redirect()->route('sellingprice.index')->with('succes','sellingprice create successfully');
    }
    public function edit($id)
    {
        $sellingprice = SellingPriceGroup::find($id);

        return view('sellingprice.edit',compact('sellingprice'));
    }
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'descriptions' => 'required',
        ]);

        $input = $request->all();    
        $inputs['updated_by'] = Auth::user()->id;
        $brand = SellingPriceGroup::find($id);
        $brand->update($input);

        return redirect()->route('sellingprice.index')
            ->with('success','Selling Price Group updated successfully');
    }
    public function destroy($id)
    {    
        $sellingprice = SellingPriceGroup::find($id);
        $sellingprice->updated_by = $id = Auth::id();
        $sellingprice->status = 0;
        $sellingprice->save();

        return redirect()->route('sellingprice.index')
                        ->with('success','sellingprice deleted successfully');
    }

    public function deactivate($id)
    {
        $course = SellingPriceGroup::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Deactivate not found.');
        }

        $course->status = 2;
        $course->save();

        return redirect()->back()->with('success', 'Deactivated successfully.');
    }

    public function activate($id)
    {
        $course = SellingPriceGroup::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Activate not found.');
        }

        $course->status = 1;
        $course->save();

        return redirect()->back()->with('success', 'Activated successfully.');
    }
}
