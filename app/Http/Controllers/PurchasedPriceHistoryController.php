<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PurchasedPriceHistory;
use App\Exports\PurchasedPriceHistoryExport;

class PurchasedPriceHistoryController extends Controller
{
    public function index(){

        $breadcrumbs = [
              ['name' => 'Purchased Price']
        ];

        $from_date = session()->get(PURCHASEEDPRICEHISTORY_FROMDATE_FILTER);
        $to_date = session()->get(PURCHASEEDPRICEHISTORY_TODATE_FILTER);

        // return $from_date;

        $purchased_price_histories = PurchasedPriceHistory::with('variation')
        ->when($from_date, function ($query) use ($from_date) {
            return $query->where('received_date', '>=', $from_date);
        })
        ->when($to_date, function ($query) use ($to_date) {
            return $query->where('received_date', '<=', $to_date);
        })->get();

        // return $purchased_price_histories;
        return view('purchasedpricehistory.index',compact('purchased_price_histories', 'breadcrumbs'));
    }

    public function export(){

        $purchased_price_history = PurchasedPriceHistory::with('variation')->get();

        return Excel::download(new PurchasedPriceHistoryExport($purchased_price_history), 'purchased-price-history.xlsx');
    }
}
