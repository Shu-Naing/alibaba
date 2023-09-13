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
              ['name' => 'Demage']
        ];

        $login_user_role = Auth::user()->roles[0]->name;
        $login_user_outlet_id = Auth::user()->outlet_id;

        $outlets = getFromOutlets(true);
        $from_date = session()->get(DA_FROMDATE_FILTER);
        $to_date = session()->get(DA_TODATE_FILTER);
        $damage_no= session()->get(DA_DAMAGE_FILTER);
        $outlet_id = session()->get(DA_OUTLETID_FILTER);
        $item_code = session()->get(DA_ITEMCODE_FILTER);

        $roles = [];
        $user = Auth::user();

        foreach($user->roles as $row) {
            $roles[] = $row->name;
        }
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
        })->when(in_array('outlet', $roles), function ($query) use ($user) {
            return $query->where('outlet_id', $user->outlet_id);
        })
        ->when($login_user_role == 'Outlet', function ($query) use ($login_user_outlet_id){
            return $query->where('outlet_id', '=', $login_user_outlet_id);
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
              ['name' => 'Demage', 'url' => route('damage.index')],
              ['name' => 'Create']
        ];
        $outlets = getFromOutlets(true);
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

        $inputs = $request->only('date', 'outlet_id', 'name', 'error', 'distination', 'damage_no');
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

            // update outlet stock overview (damage so issued)
            $month = date('n',strtotime($damage->date));
            $year = date('Y',strtotime($damage->date));

            $outletleveloverview = OutletLevelOverview::select('outlet_level_overviews.*')
            ->where('outlet_id',$request->outlet_id)
            ->where('item_code',$request->item_code[$variationId])
            ->whereMonth('date',$month)
            ->whereYear('date',$year)->first();

            if($outletleveloverview){ 
                $issued_qty = $outletleveloverview->issued_qty + $request->quantity[$variationId];    
                $input = [];
                $input['issued_qty'] = $issued_qty;
                $input['balance'] = ($outletleveloverview->opening_qty + $outletleveloverview->receive_qty) - $issued_qty;
                $input['updated_by'] = Auth::user()->id;
                $outletleveloverview->update($input);
            }else {                
                $input = [];
                $input['date'] = $damage->date;
                $input['outlet_id'] = $outlet_id;
                $input['item_code'] = $request->item_code[$variationId];
                $input['issued_qty'] = $request->quantity[$variationId];
                $input['balance'] = (0 + 0) - $request->quantity[$variationId];
                $input['created_by'] = Auth::user()->id;
                OutletLevelOverview::create($input);
            }
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
        // return $id;
        $breadcrumbs = [
            ['name' => 'Demage', 'url' => route('damage.index')],
            ['name' => 'Detail & Edit']
        ];
        $outlets = getFromOutlets(true);
        $damages = Damage::findorFail($id);
        $damage_items = DamageItems::where('damage_id', $id)->get();
        
        // return $damage_items;
        // return $from_date;
        return view('damage.edit', compact('breadcrumbs', 'damages', 'damage_items', 'outlets'));
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
        // return $request;
        $damage = Damage::where('id',$id)->first();
        if($damage) {
            $input = $request->all();
            $input['updated_by'] = Auth::user()->id;
            $damage->update($input);
        }
        
        return redirect()->route('damage.index')->with('success', 'issue created successfully');
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

        $roles = [];
        $user = Auth::user();
        
        foreach($user->roles as $row) {
            $roles[] = $row->name;
        }

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

        return 'D-'.$newString.'-'.$date.$counter;   
    }
}
