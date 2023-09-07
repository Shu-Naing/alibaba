<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Damage;
use App\Models\DamageItems;
use Illuminate\Http\Request;
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
        $damage_no= session()->get(DA_DAMAGE_FILTER);
        $outlet_id = session()->get(DA_OUTLETID_FILTER);
        $item_code = session()->get(DA_ITEMCODE_FILTER);

        $roles = [];
        $user = Auth::user();
        // return $user->roles;
        foreach($user->roles as $row) {
            $roles[] = $row->name;
        }
        // return $roles;
        


        $damages = Damage::join('damage_items', 'damage_items.damage_id', 'damages.id')
        ->when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($damage_no, function ($query) use ($damage_no) {
            return $query->where('damage_no', '=', $damage_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })->when(in_array('outlet', $roles), function ($query) use ($user) {
            return $query->where('outlet_id', $user->outlet_id);
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
        // return $request;
        $this->validate($request, [
            'date' => 'required',
            'outlet_id' => 'required',
            'quantity' => 'required',
            'damage_no' => 'required|unique:damages',
        ]);

        $inputs = $request->only('date', 'outlet_id', 'name', 'amount', 'action', 'error', 'distination', 'damage_no');
        $inputs['created_by'] = Auth::user()->id;
        $damage = Damage::create($inputs);

        foreach( $request->variation_id as $variationId ) {
            $inputs = [];
            $inputs['damage_id'] = $damage->id;
            $inputs['item_code'] = $request->item_code[$variationId];
            $inputs['point'] = $request->points[$variationId];
            $inputs['ticket'] = $request->tickets[$variationId];
            $inputs['kyat'] = $request->kyat[$variationId];
            $inputs['purchase_price'] = $request->purchase_price[$variationId];
            $inputs['total'] = $request->total[$variationId];
            $inputs['reason'] = $request->reason[$variationId];
            $inputs['quantity'] = $request->quantity[$variationId];
            $inputs['created_by'] = Auth::user()->id;
            // return $inputs;
            DamageItems::create($inputs);
        }
        

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
        $damage_no= session()->get(DA_DAMAGE_FILTER);
        $outlet_id = session()->get(DA_OUTLETID_FILTER);
        $item_code = session()->get(DA_ITEMCODE_FILTER);

        $damages = Damage::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($damage_no, function ($query) use ($damage_no) {
            return $query->where('damage_no', '=', $damage_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })
        ->get();
        
        return Excel::download(new DamagesExport($damages), 'damages.xlsx');
    }

    public function demageGenerateCode(Request $request) {
        
        $date = date("dmY"); 
        $outletName = $request->outlet_id;
        $newString = str_replace(" ", "-", $outletName);
        $counter = 1;
        $data = Damage::orderBy('created_at', 'desc')->value('damage_No');

        if($data) {
            $lastThreeChars = substr($data, -3);
            $counter = intval($lastThreeChars);
            $counter++;
        }
    
        $counter = str_pad($counter, 3, 0, STR_PAD_LEFT);

        return 'D-'.$newString.$date.$counter;   
    }
}
