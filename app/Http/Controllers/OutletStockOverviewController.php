<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlets;
use App\Models\Machines;
use App\Models\OutletStockOverview;
use App\Models\Variation;
use App\Models\MachineVariant;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutletstockoverviewSampleExport;
use App\Imports\OutletstockoverviewsImport;
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
            'item_code' => 'required',
            'opening_qty' => 'required',
        ]);

        $month = date('n', strtotime($request->date));
        $year = date('Y', strtotime($request->date));
        $outletstockoverview = OutletStockOverview::select('outlet_stock_overviews.*')
        ->where('outlet_id', $request->outlet_id)
        ->where('machine_id', $request->machine_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('item_code',$request->item_code)->first();

        if($outletstockoverview){     
            $opening_qty = $outletstockoverview->opening_qty + $request->opening_qty;
            $input = [];
            $input['opening_qty'] = $opening_qty;
            $input['balance'] = ($opening_qty + $outletstockoverview->receive_qty) - $outletstockoverview->issued_qty;
            $input['updated_by'] = Auth::user()->id;
            $outletstockoverview->update($input);
        }else {
            $input = [];
            $input['date'] = $request->date;
            $input['outlet_id'] = $request->outlet_id;
            $input['machine_id'] = $request->machine_id;
            $input['item_code'] = $request->item_code;
            $input['opening_qty'] = $request->opening_qty;
            $input['balance'] = $request->opening_qty;
            $input['created_by'] = Auth::user()->id;
            OutletStockOverview::create($input);
        }

        return redirect()->back()->with('success', 'Opening Qty is create or update success');
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
        // return $request;
        $physical_qty = $request->physical_qty;
        $balance_qty =  $request->balance_qty;
        if ($balance_qty < 0) {
            $difference_qty =  $balance_qty + $physical_qty;
        } else {
            $difference_qty =  $balance_qty - $physical_qty;
        }

        $input = [];
        $input['physical_qty'] = $physical_qty;
        $input['difference_qty'] = $difference_qty;
        $input['updated_by'] = auth()->user()->id;
        $outletstocksoverview = OutletStockOverview::find($request->id);
        $outletstocksoverview->update($input);
        return "success data";
    }

    public function exportSampleOutletstockoverview(Request $request) {
        return Excel::download(new OutletstockoverviewSampleExport(), 'opening_stock_quantity.xlsx');
    }

    public function importOutletstockoverview(Request $request) {
        $file = $request->file('file');

        try {
            Excel::import(new OutletstockoverviewsImport, $file);
            return redirect()->back()->with('success', 'Products imported successfully.');
        }catch (Exeption $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        
    }

    public function getoutletItem(Request $request) {
        $outletId = $request->outlet_id;

        $item_codes = getOutletItem($request->outlet_id);
        
        return $item_codes;
    }

    
}