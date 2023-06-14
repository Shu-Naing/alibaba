<?php

namespace App\Http\Controllers;

use App\Models\Temp;
use App\Models\Product;
use App\Models\Variation;
use App\Models\OutletItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PosController extends Controller
{
    public function index(Request $request){
        $user_outlet_id = Auth::user()->outlet->id;
        if($request->has('filter')){
            if($request->get('filter') === 'kyat'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) {
                    $query->whereNotNull('kyat');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }elseif($request->get('filter') === 'point'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) {
                    $query->whereNotNull('points');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }elseif($request->get('filter') === 'ticket'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) {
                    $query->whereNotNull('tickets');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }
         }else{
            
            $outlet_items = OutletItem::with('variation','variation.product')->where('outlet_id',$user_outlet_id)->get();
         }
        
        //  return $outlet_items;
       

        // $outlet_items = OutletItem::with('variation','variation.product')->where('outlet_id',$user_outlet_id)->get();
        
        $user_id = Auth::user()->id;
        $temps = Temp::with('variation','variation.product')->where('created_by',$user_id)->get();
        
       
        return view('pos.index',compact('outlet_items','temps'));
    }
    

    public function addItemPos(Request $request)
    {
        $user_id = Auth::user()->id;
        Temp::updateOrCreate(
            ['variation_id' => $request->variation_id,'created_by' => $user_id], // Search criteria
            ['quantity' => \DB::raw('quantity + 1') , 'created_by' => $user_id, 'updated_by' => $user_id] // Data to update or create
        );
        
        return response()->json(['message' => 'Product added to cart']);
    }

    public function removeItemPos(Request $request,$temp_id){
        // Temp::delete(
        //     ['variation_id' => $request->variation_id,'created_by' => $user_id], // Search criteria
        //     ['quantity' => \DB::raw('quantity + 1') , 'created_by' => $user_id, 'updated_by' => $user_id] // Data to update or create
        // );
        $temp = Temp::find($temp_id);
        return $temp;
    }

   
}
