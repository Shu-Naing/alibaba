<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(){

        $variations = Variation::with('product')->get();
        // return $variations;
        return view('pos.index',compact('variations'));
    }


    public function getProductData($variation_id){

        $variations = Variation::with('product')->where('id',$variation_id)->get();
        return $variations;
    }

   
}
