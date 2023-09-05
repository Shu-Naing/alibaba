<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\OutletItem;
use Illuminate\Http\Request;
use App\Models\OutletItemData;
use App\Exports\PurchaseExport;
use App\Exports\PurchaseAddExport;
use App\Imports\PurchaseAddImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PurchasedPriceHistory;
use App\Exports\PurchaseAddSampleExport;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Purchase']
        ];

        $purchases = OutletItemData::select('grn_no', 'received_date')
                    ->groupBy('grn_no', 'received_date')
                    ->whereNotNull('grn_no')
                    ->where('grn_no', '!=', '')
                    ->get();
        // return $purchases;
        return view('purchase.index', compact('purchases', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Purchase', 'url' => route('purchase.index')],
              ['name' => 'create']
        ];
        return view('purchase.create', compact('breadcrumbs'));
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

        $this->validate($request,[ 
            'grn_no' => 'required',            
            'received_date' => 'required',
            'country' => 'required',
            'tickets' => 'required',
            'points' => 'required',
            'kyat' => 'required',
            'purchased_price' => 'required',
            'quantity' => 'required',
        ]);

        foreach($request->variationId as $id) {
            $outlet_item_id = OutletItem::where('outlet_id', MAIN_INV_ID)->where('variation_id', $id)->first();
            $total = $request->purchased_price[$id] * $request->quantity[$id];
            // return $outlet_item_id;

            if ($outlet_item_id) {
                OutletItemData::create([
                    'outlet_item_id' => $outlet_item_id->id,
                    'points' => $request->points[$id],
                    'tickets' => $request->tickets[$id],
                    'kyat' => $request->kyat[$id],
                    'purchased_price' => $request->purchased_price[$id],
                    'quantity' => $request->quantity[$id],
                    'grn_no' => $request->grn_no,
                    'received_date' => $request->received_date,
                    'country' => $request->country,
                    'created_by' => Auth::user()->id,
                ]);

                PurchasedPriceHistory::create([
                    'variation_id' =>  $id,
                    'purchased_price' => $request->purchased_price[$id],
                    'points' => $request->points[$id],
                    'tickets' => $request->tickets[$id],
                    'kyat' => $request->kyat[$id],
                    'quantity' => $request->quantity[$id],
                    'grn_no' => $request->grn_no,
                    'total' => $total,
                    'received_date' => $request->received_date,
                    'created_by' => Auth::user()->id,
                ]);
            } else {
                return redirect()->back()->with('errorPurchase', 'Item is require, You need to add item.');
            }
        }

        return redirect()->back()->with('success', 'Products imported successfully.');

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
              ['name' => 'Purchase', 'url' => route('purchase.index')],
              ['name' => 'Purchase Items']
        ];

        $item_code = session()->get(PURCHASE_ITEMCODE_FILTER);

        $purchaseItems = OutletItemData::join('outlet_items', 'outlet_items.id', '=', 'outlet_item_data.outlet_item_id' )
        ->join('variations', 'variations.id', '=', 'outlet_items.variation_id')
        ->where('grn_no', $id)
        ->when($item_code, function ($query) use ($item_code) {
            return $query->where('variations.item_code', '=', $item_code);
        })
        ->get();
        return view('purchase.show', compact('purchaseItems', 'breadcrumbs'));
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

    public function exportSamplePurchaseAdd(Request $request) {
        // return "hello";
        return Excel::download(new PurchaseAddSampleExport(), 'purchase_add.xlsx');

    }

    public function importPurchaseAdd(Request $request) {
        // return $request;

        $file = $request->file('file');

        try {
            Excel::import(new PurchaseAddImport, $file);
            return redirect()->back()->with('success', 'Purchase Add imported successfully.');
        }catch (Exeption $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

    }

    // excel exprot
    public function purchaseDetailExport($grn_no)
    {
        $item_code = session()->get(PURCHASE_ITEMCODE_FILTER);
        // return $grn_no;

        $purchaseItems = OutletItemData::join('outlet_items', 'outlet_items.id', '=', 'outlet_item_data.outlet_item_id' )
        ->join('variations', 'variations.id', '=', 'outlet_items.variation_id')
        ->where('grn_no', $grn_no)
        ->when($item_code, function ($query) use ($item_code) {
            return $query->where('variations.item_code', '=', $item_code);
        })
        ->get();

        // return $purchaseItems;
        
        return Excel::download(new PurchaseExport($purchaseItems), 'purchase.xlsx');
    }

    public function purchasedetailcountry(Request $request)
    {   
        $country =  $request->idCountry;
        $grn_no = $request->grn_no;
        $received_date = $request->received_date;
        $outlet_item_data = OutletItemData::where('grn_no', $grn_no)->where('received_date', $received_date)->get();
        
        if($outlet_item_data) {
            foreach ($outlet_item_data as $item) {
                $item->update(['country' => $country]);
            }
        }
        return "you update country is success";
    }
}
