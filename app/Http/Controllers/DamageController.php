<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Damage;
use Auth;
use App\Exports\DamagesExport;
use Maatwebsite\Excel\Facades\Excel;

class DamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Damage']
        ];
        $outlets = getOutlets();
        $from_date = session()->get(DA_FROMDATE_FILTER);
        $to_date = session()->get(DA_TODATE_FILTER);
        $voucher_no= session()->get(DA_VOUCHERNO_FILTER);
        $outlet_id = session()->get(DA_OUTLETID_FILTER);
        $item_code = session()->get(DA_ITEMCODE_FILTER);

        $damages = Damage::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($voucher_no, function ($query) use ($voucher_no) {
            return $query->where('voucher_no', '=', $voucher_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })
        ->get();

        // return $from_date;
        return view('damage.index', compact('breadcrumbs', 'damages', 'outlets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Damage', 'url' => route('damage.index')],
              ['name' => 'Create']
        ];
        $outlets = getOutlets();
        return view('damage.create', compact('breadcrumbs', 'outlets'));
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
            'date' => 'required',
            'voucher_no' => 'required|unique:damages',
            'outlet_id' => 'required',
            'item_code' => 'required|unique:damages',
            'quantity' => 'required',
            'ticket' => 'required',
            'original_cost' => 'required',
            'amount_ks' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'error' => 'required',
            'action' => 'required',
            'distination' => 'required',
            'damage_no' => 'required|unique:damages',
            'column1' => 'required',
        ]);

        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        Damage::create($inputs);

        return redirect()->route('damage.index')->with('success','Damage is created successfully');;
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

    // excel export
    public function exportDamage()
    {
        $outlets = getOutlets();
        $from_date = session()->get(DA_FROMDATE_FILTER);
        $to_date = session()->get(DA_TODATE_FILTER);
        $voucher_no= session()->get(DA_VOUCHERNO_FILTER);
        $outlet_id = session()->get(DA_OUTLETID_FILTER);
        $item_code = session()->get(DA_ITEMCODE_FILTER);

        $damages = Damage::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($voucher_no, function ($query) use ($voucher_no) {
            return $query->where('voucher_no', '=', $voucher_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })
        ->get();
        
        return Excel::download(new DamagesExport($damages), 'damages.xlsx');
    }
}
