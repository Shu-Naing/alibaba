<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $users = Products::where([
            ['product_name', '!=', Null],
            [function ($query) use ($request) {
                if (($s = $request->s)) {
                    $query->orWhere('product_name', 'LIKE', '%' . $s . '%')
                        ->orWhere('item_code', 'LIKE', '%' . $s . '%')
                        ->get();
                }
            }]
        ])->paginate(6);

        return view('testing.searchtesting', compact('users'));
    }
}
