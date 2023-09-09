<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {

        $breadcrumbs = [
              ['name' => 'Companies']
        ];

        $companies = Company::where('status',1)->get();

        return view('companies.index', ['companies' => $companies, 'breadcrumbs' => $breadcrumbs]);

    }

    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Companies', 'url' => route('companies.index')],
              ['name' => 'Create']
        ];

        return view('companies.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:companies',
        ]);

        $country = Country::create([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('companies.index')
            ->with('success','Company created successfully.');
    }

    public function edit($id)
    {
        $breadcrumbs = [
              ['name' => 'Companies', 'url' => route('companies.index')],
              ['name' => 'Edit']
        ];

        $company = Country::find($id);

        return view('companies.edit',compact('company', 'breadcrumbs'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|unique:companies,name,'.$company->id,
        ]);

        $country->update($request->all());

        return redirect()->route('companies.index')
            ->with('success','Company updated successfully');
    }

    public function destroy($id)
    {    
        $company = Company::find($id);
        $company->updated_by = Auth::user()->id;
        $company->status = 0;
        $company->save();

        return redirect()->route('companies.index')
            ->with('success','Company deleted successfully');
    }
}
