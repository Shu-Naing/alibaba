<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pos;
use App\Exports\SellExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use DB;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Sell']
        ];

        $auth_id = Auth::user()->id;

        $posSellLists = Pos::select('*','users.outlet_id','users.name',DB::raw('sum(pos_items.quantity) as quantity'))
        ->join('users','users.id','pos.created_by')
        ->join('pos_items','pos.id','pos_items.pos_id');

        if(session()->get(SELL_INVOICE_FILTER)){
            $posSellLists = $posSellLists->where('invoice_no',session()->get(SELL_INVOICE_FILTER));
        } 
        if(session()->get(SELL_PAYMENTTYPE_FILTER)){
            $posSellLists = $posSellLists->where('payment_type',session()->get(SELL_PAYMENTTYPE_FILTER));
        } 
        if(session()->get(SELL_FROMDATE_FILTER)){
            $posSellLists = $posSellLists->whereDate('created_at','>=',session()->get(SELL_FROMDATE_FILTER));
        } 
        if(session()->get(SELL_TODATE_FILTER)){
            $posSellLists = $posSellLists->whereDate('created_at','<=',session()->get(SELL_TODATE_FILTER));
        }

        if(session()->get(SELL_OUTLETID_FILTER)){
            $posSellLists = $posSellLists->where('users.outlet_id',session()->get(SELL_OUTLETID_FILTER));
        }

        if(is_outlet_user()){
            $posSellLists = $posSellLists->where('created_by',$auth_id);
        }

        $posSellLists = $posSellLists->where('invoice_no','<>','')->groupBy('pos.id')->get();

        $outlets = getOutlets(true);

        // return $posLists;
        return view('sell.index', compact('breadcrumbs', 'posSellLists','outlets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbs = [
            ['name' => 'Sell', 'url' => route('sell.index')],
            ['name' => 'Detail']
        ];

        $sellDetailLists = Pos::join('pos_items', 'pos_items.pos_id', '=', 'pos.id')
        ->join('variations', 'variations.id', 'pos_items.variation_id')
        ->join('products', 'products.id', 'variations.product_id')
        ->where('pos.id', '=', $id)
        ->get();
        // return $sellDetailLists;
        // return $sellDetailLists[0]->invoice_no;

        return view('sell.show', compact('breadcrumbs', 'sellDetailLists'));
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

    public function search(Request $request){
        session()->start();
        session()->put(SELL_INVOICE_FILTER, $request->invoice_id);
        session()->put(SELL_PAYMENTTYPE_FILTER, $request->payment_type);
        session()->put(SELL_FROMDATE_FILTER, $request->from_date);
        session()->put(SELL_TODATE_FILTER, $request->to_date);
        session()->put(SELL_OUTLETID_FILTER, $request->outlet_id);

        return redirect()->route('sell.index');
    }

    public function reset(){
        session()->forget([
            SELL_INVOICE_FILTER, 
            SELL_PAYMENTTYPE_FILTER, 
            SELL_FROMDATE_FILTER, 
            SELL_TODATE_FILTER,
            SELL_OUTLETID_FILTER           
        ]);
        return redirect()->route('sell.index');
    }

    public function sellExport()
    {
        $auth_id = Auth::user()->id;

        $posSellLists = Pos::select('*','users.outlet_id','users.name',DB::raw('sum(pos_items.quantity) as quantity'))
        ->join('users','users.id','pos.created_by')
        ->join('pos_items','pos.id','pos_items.pos_id');

        if(session()->get(SELL_INVOICE_FILTER)){
            $posSellLists = $posSellLists->where('invoice_no',session()->get(SELL_INVOICE_FILTER));
        } 
        if(session()->get(SELL_PAYMENTTYPE_FILTER)){
            $posSellLists = $posSellLists->where('payment_type',session()->get(SELL_PAYMENTTYPE_FILTER));
        } 
        if(session()->get(SELL_FROMDATE_FILTER)){
            $posSellLists = $posSellLists->whereDate('created_at','>=',session()->get(SELL_FROMDATE_FILTER));
        } 
        if(session()->get(SELL_TODATE_FILTER)){
            $posSellLists = $posSellLists->whereDate('created_at','<=',session()->get(SELL_TODATE_FILTER));
        }

        if(session()->get(SELL_OUTLETID_FILTER)){
            $posSellLists = $posSellLists->where('users.outlet_id',session()->get(SELL_OUTLETID_FILTER));
        }

        if(is_outlet_user()){
            $posSellLists = $posSellLists->where('created_by',$auth_id);
        }

        $posSellLists = $posSellLists->where('invoice_no','<>','')->groupBy('pos.id')->get();

        // return $purchaseItems;
        
        return Excel::download(new SellExport($posSellLists), 'sell.xlsx');
    }

    
}
