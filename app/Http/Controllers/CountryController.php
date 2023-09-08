<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    public function index()
    {

        $breadcrumbs = [
              ['name' => 'Countries']
        ];

        $countries = Country::where('status',1)->get();

        return view('countries.index', ['countries' => $countries, 'breadcrumbs' => $breadcrumbs]);

    }

    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Countries', 'url' => route('countries.index')],
              ['name' => 'Create']
        ];

        return view('countries.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:countries',
        ]);

        $country = Country::create([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('countries.index')
            ->with('success','Country created successfully.');
    }

    public function edit($id)
    {
        $breadcrumbs = [
              ['name' => 'Countries', 'url' => route('countries.index')],
              ['name' => 'Edit']
        ];

        $country = Country::find($id);

        return view('countries.edit',compact('country', 'breadcrumbs'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|unique:countries,name,'.$country->id,
        ]);

        $country->update($request->all());

        return redirect()->route('countries.index')
            ->with('success','Country updated successfully');
    }

    public function destroy($id)
    {    
        $country = Country::find($id);
        $country->updated_by = Auth::user()->id;
        $country->status = 0;
        $country->save();

        return redirect()->route('countries.index')
            ->with('success','Country deleted successfully');
    }
}
