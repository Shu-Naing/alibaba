<?php

namespace App\Http\Controllers;

use App\Models\SizeVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SizeVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Size Variants']
        ];
        $data = SizeVariant::orderBy('id','DESC')->get();

        return view('size-variants.index',compact('data','breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Size Variants', 'url' => route('size-variant.index')],
            ['name' => 'Create']
      ];

      return view('size-variants.create',compact('breadcrumbs'));
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
            'value' => 'required|unique:size_variants,value',
        ]);

        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
    
        $sizeVariant = SizeVariant::create($input);
    
        return redirect()->route('size-variant.index')
                ->with('success','Size Variant created successfully');     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SizeVariant  $sizeVariant
     * @return \Illuminate\Http\Response
     */
    public function show(SizeVariant $sizeVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SizeVariant  $sizeVariant
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $breadcrumbs = [
            ['name' => 'Size Variants', 'url' => route('size-variant.index')],
            ['name' => 'Edit']
      ];

      $sizeVariant = SizeVariant::find($id);

      return view('size-variants.edit',compact('sizeVariant','breadcrumbs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SizeVariant  $sizeVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SizeVariant $sizeVariant)
    {
        $request->validate([
            'value' => 'required|unique:size_variants,value,'.$sizeVariant->id,
        ]);

        $sizeVariant->update($request->all());

        return redirect()->route('size-variant.index')
            ->with('success','Size Variant updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SizeVariant  $sizeVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SizeVariant::find($id)->delete();
        return redirect()->route('size-variant.index')
                        ->with('success','Size Variant deleted successfully');
    }
}
