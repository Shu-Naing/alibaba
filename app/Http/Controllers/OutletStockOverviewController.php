<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlets;
use App\Models\Machines;
use App\Models\OutletStockOverview;
use App\Models\Variation;
use App\Models\MachineVariant;
use Auth;

class OutletStockOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlets = getOutlets();
        $machines = getMachinesWithOutletID();
        $outletstocks = OutletStockOverview::select('outlet_stock_overviews.*', 'machines.name')->join('machines', 'machines.id', '=', 'outlet_stock_overviews.machine_id')->get();
        return view('outletstockoverview.index', compact('outlets', 'machines', 'outletstocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $outlets = getOutlets();
        $machines = getMachinesWithOutletID($id);
        // $item_codes = Machines::with('machine_variants.variants')->where('outlet_id', $id)->where('machine_id', )->get();
        
                // return $item_codes1;
        return view('outletstockoverview.create', compact('outlets', 'machines', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[ 
            'date' => 'required',            
            'outlet_id' => 'required',
            'machine_id' => 'required',
        ]);
        $input = [];
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $outletstockeoverview = OutletStockOverview::create($input); 
        return redirect()->route('outletstockoverview.edit',$outletstockeoverview->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$id is outletstockoverview id        
        $overview = OutletStockOverview::find($id);
        $outlets = getOutlets();
        $machines = getMachinesWithOutletID($overview->outlet_id);
        $item_codes = getOutletItem($overview->outlet_id);
        return view('outletstockoverview.edit',compact('overview','outlets','machines','item_codes'));
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
        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $outletstocksoverview = OutletStockOverview::find($id);
        $outletstocksoverview->update($input);        
        return redirect()->route('outletstockoverview.create', $request->outlet_id);
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

    public function checkoutletstockoverview(Request $request) 
    {
        $outletstockoverview_id = $request->id;
        $check =  $request->check;
        $c = 0;
        if($check == 'true') {
            $c = 1;
        }
        $input = [];
        $input['is_check'] = $c;
        $input['updated_by'] = auth()->user()->id;
        $outletstocksoverview = OutletStockOverview::find($outletstockoverview_id);
        $outletstocksoverview->update($input);
        // return $input;
        return "success data";
    }

    public function updatephysicalqty(Request $request) {
        $physical_qty = $request->physical_qty;
        $balance_qty =  $request->balance_qty;
        $difference_qty =  $balance_qty - $physical_qty;

        $input = [];
        $input['physical_qty'] = $physical_qty;
        $input['difference_qty'] = $difference_qty;
        $input['updated_by'] = auth()->user()->id;
        $outletstocksoverview = OutletStockOverview::find($request->id);
        $outletstocksoverview->update($input);
        return "success data";
    }

    
}