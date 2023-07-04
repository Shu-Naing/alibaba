<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PurchasedPriceHistory;
use App\Exports\PurchasedPriceHistoryExport;

class PurchasedPriceHistoryController extends Controller
{
    public function index(){

        $purchased_price_histories = PurchasedPriceHistory::with('variation')->get();

        return view('purchasedpricehistory.index',compact('purchased_price_histories'));
    }

    public function export(){

        $purchased_price_history = PurchasedPriceHistory::with('variation')->get();

        return Excel::download(new PurchasedPriceHistoryExport($purchased_price_history), 'purchased-price-history.xlsx');
    }
}
