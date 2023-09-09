<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlets;
use App\Models\Adjustment;
use App\Models\OutletItemData;
use Auth;
use App\Exports\AdjustmentsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Adjustments']
        ];

        $outlets = getOutlets();
        // $adjustments = Adjustment::all();

        $from_date = session()->get(ADJ_FROMDATE_FILTER);
        $to_date = session()->get(ADJ_TODATE_FILTER);
        $adj_no= session()->get(ADJ_ADJNO_FILTER);
        $outlet_id = session()->get(ADJ_OUTLETID_FILTER);
        $item_code = session()->get(ADJ_ITEMCODE_FILTER);

        $adjustments = Adjustment::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($adj_no, function ($query) use ($adj_no) {
            return $query->where('adj_no', '=', $adj_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })
        ->get();
        return view('adjustment.index', compact('breadcrumbs', 'adjustments', 'outlets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Adjustment', 'url' => route('adjustment.index')],
              ['name' => 'Create']
        ];

        $outlets = getOutlets();
        return view('adjustment.create', compact('breadcrumbs', 'outlets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->adjustment_qty;
        $this->validate($request,[ 
            'adj_no' => 'required|unique:adjustments',
            'date' => 'required',  
            'outlet_id' => 'required',   
            'item_code' => 'required',   
            'type' => 'required',   
            'adjustment_qty' => 'required',
        ]);

        $inputs = $request->all();
        $inputs['created_by'] = Auth::user()->id;
        Adjustment::create($inputs);

        $outlet_item_data = OutletItemData::select('outlet_item_data.id','outlet_item_data.quantity')
        ->join('outlet_items', 'outlet_item_data.outlet_item_id', '=', 'outlet_items.id')
        ->join('variations', 'variations.id', '=', 'outlet_items.variation_id')
        ->where('outlet_items.outlet_id', $request->outlet_id)
        ->where('variations.item_code', $request->item_code)
        ->where('outlet_item_data.quantity', '>', 0)
        ->orderBy('outlet_item_data.created_at', 'desc')
        ->first();

        if ($outlet_item_data) {
            if($request->type === '1') {
                $updateQuantity = $outlet_item_data->quantity + intval($request->adjustment_qty);
            }else {
                $updateQuantity = $outlet_item_data->quantity - intval($request->adjustment_qty);
            }

            $outlet_item_data->update(['quantity' => $updateQuantity]);
           
        }

        return redirect()->route('adjustment.index')->with('success','Adjustment is created successfully');
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

    // excel exprot
    public function exportAdjustment()
    {
        $outlets = getOutlets();

        $from_date = session()->get(ADJ_FROMDATE_FILTER);
        $to_date = session()->get(ADJ_TODATE_FILTER);
        $adj_no= session()->get(ADJ_ADJNO_FILTER);
        $outlet_id = session()->get(ADJ_OUTLETID_FILTER);
        $item_code = session()->get(ADJ_ITEMCODE_FILTER);

        $adjustments = Adjustment::when($from_date, function ($query) use ($from_date) {
            return $query->where('date', '>=', $from_date);
        })->when($to_date, function ($query) use ($to_date) {
            return $query->where('date', '<=', $to_date);
        })->when($adj_no, function ($query) use ($adj_no) {
            return $query->where('adj_no', '=', $adj_no);
        })->when($outlet_id, function ($query) use ($outlet_id) {
            return $query->where('outlet_id', '=', $outlet_id);
        })->when($item_code, function ($query) use ($item_code) {
            return $query->where('item_code', '=', $item_code);
        })
        ->get();
        
        return Excel::download(new AdjustmentsExport($outlets, $adjustments), 'adjustments.xlsx');
    }

    public function adjGenerateCode(Request $request) {
        $date = date("dmY"); 
        $outletName = $request->outlet_id;
        $newString = str_replace(" ", "-", $outletName);
        $counter = 1;
        $data = Adjustment::orderBy('created_at', 'desc')->value('adj_no');

        if($data) {
            $lastThreeChars = substr($data, -3);
            $counter = intval($lastThreeChars);
            $counter++;
        }
    
        $counter = str_pad($counter, 3, 0, STR_PAD_LEFT);

        return 'D-'.$newString.'-'.$date.$counter;      
    }
}
