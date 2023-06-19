<?php

namespace App\Http\Controllers;

use App\Models\Pos;
use App\Models\Temp;
use App\Models\PosItem;
use App\Models\Product;
use App\Models\Variation;
use App\Models\OutletItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PosController extends Controller
{
    public function index(Request $request){

        $pos_id = $request->session()->get('pos-id');
        $user_outlet_id = Auth::user()->outlet->id;
        $search_key = $request->get('key');
        if($request->has('filter') && $request->has('key')){
            if($request->get('filter') === 'kyat'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) use ($search_key) {
                    $query->whereNotNull('kyat')->where('item_code', 'like', '%' . $search_key . '%');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }elseif($request->get('filter') === 'point'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) use ($search_key) {
                    $query->whereNotNull('points')->where('item_code', 'like', '%' . $search_key . '%');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }elseif($request->get('filter') === 'ticket'){
                $outlet_items = OutletItem::whereHas('variation', function ($query) use ($search_key) {
                    $query->whereNotNull('tickets')->where('item_code', 'like', '%' . $search_key . '%');
                })
                ->with('variation.product')
                ->where('outlet_id', $user_outlet_id)
                ->get();
            }
         }elseif($request->has('filter') && !$request->has('key')){
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
            Session::forget('pos-success');
            Session::forget('pos-id');
            $outlet_items = OutletItem::with('variation','variation.product')->where('outlet_id',$user_outlet_id)->get();
         }
        
        $user_id = Auth::user()->id;
        $temps = Temp::with('variation','variation.product')->where('created_by',$user_id)->get();
        $pos_items = PosItem::whereHas('pos', function ($query) use ($user_id,$pos_id) {
            $query->where('created_by',$user_id)->where('id',$pos_id);
        })->with('variation','pos')->get();
 
        
       
        return view('pos.index',compact('outlet_items','temps','pos_items'));
    }
    

    public function addItemPos(Request $request)
    {
       
        $user_id = Auth::user()->id;
        Temp::updateOrCreate(
            ['variation_id' => $request->variation_id,'created_by' => $user_id], // Search criteria
            ['quantity' => \DB::raw('quantity + 1') ,'variation_value' => $request->variation_value , 'created_by' => $user_id, 'updated_by' => $user_id] // Data to update or create
        );
        
        return response()->json(['message' => 'Product added to cart']);
    }

    public function updateItemPos(Request $request){
        // return $request;
        $temp = Temp::find($request->temp_id)->update([
            'quantity' => $request->qty
        ]);

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function removeItemPos(Request $request){

        if($request->temp_id === 'all'){
            $user_id = Auth::user()->id;
            $temp = Temp::where('created_by',$user_id)->delete();
        }else{
            $temp = Temp::find($request->temp_id)->delete();
        }

        return response()->json(['message' => 'Product removed successfully']);
    }

    public function addPos(Request $request){

        // return $request;
        $user_id = Auth::user()->id;
        $outlet_id = Auth::user()->outlet->id;
        $temps = Temp::where('created_by',$user_id)->get();

        // return $temp;
        $pos = new Pos;
        $pos->invoice_no = Str::random(8);
        $pos->total = $request->total;
        $pos->payment_type = $request->payment_type;
        $pos->created_by = $user_id;
        $pos->updated_by = $user_id;
        $pos->save();

        foreach($temps as $temp){
            $pos_item = new PosItem;
            $pos_item->pos_id = $pos->id;
            $pos_item->variation_id = $temp->variation_id;
            $pos_item->quantity = $temp->quantity;
            $pos_item->variation_value = $temp->variation_value;
            $pos_item->save();

            OutletItem::where('outlet_id',$outlet_id)->where('variation_id',$temp->variation_id)->decrement('quantity', $temp->quantity);
        }

        Temp::where('created_by',$user_id)->delete();
        
        // Session::forget('pos-success');
        session()->put('pos-success',true);
        session()->put('pos-id',$pos->id);
        return response()->json(['message' => 'Pos added successfully']);
    }

    public function searchPosProduct(Request $request){

        // $products = Product::where('name', 'like', '%keyword%')
        //            ->orWhere('description', 'like', '%keyword%')
        //            ->get();
        $user_outlet_id = Auth::user()->outlet->id;
        $search_key = $request->key;
        $outlet_items = OutletItem::whereHas('variation', function ($query) use ($search_key) {
            $query->where('item_code', 'like', '%' . $search_key . '%');
        })->with('variation','variation.product')->where('outlet_id',$user_outlet_id)->get();
        $user_id = Auth::user()->id;
        $temps = Temp::with('variation','variation.product')->where('created_by',$user_id)->get();
        $pos_items = PosItem::whereHas('pos', function ($query) use ($user_id) {
            $query->where('created_by',$user_id);
        })->with('variation','pos')->get();
        // return $pos_items;
        
       
        return view('pos.index',compact('outlet_items','temps','pos_items'));
    }

   
}
