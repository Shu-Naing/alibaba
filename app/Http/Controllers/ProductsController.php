<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Units;
use App\Models\Brands;
use App\Models\Outlets;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Categories;
use App\Models\OutletItem;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\DistributeProducts;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsSampleExport;
use App\Models\OutletDistributeProduct;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {

        $products = Variation::with('product','outlet_item','product.brand','product.category','product.unit')->get();

            // return $products;
    
        return view('products.index', compact('products'));
    }

    public function create()
    {

        $brands = Brands::all();
        $categories = Categories::all();
        $units = Units::all();
        return view('products.create',compact('brands','categories','units'));
    }

    public function store(Request $request)
    {
    //    return $request;
        $validator = Validator::make($request->all(), [
            "product_name" => "required|unique:products",
            "category_id" => "required",
            "brand_id" => "required",
            "unit_id" => "required",
            "company_name" => "required",
            "country" => "required",
            "sku" => "required|unique:products",
            "received_date" => "required",
            "expired_date" => "required",
            "description" => "required",

        ]);

        if ($validator->fails()) {
            return redirect()->route('products.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Create a new product instance
        $product = new Product;
        $product->product_name = $request->product_name;
        $product->unit_id = $request->unit_id;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->company_name = $request->company_name;
        $product->country = $request->country;
        $product->sku = $request->sku; 
        $product->received_date = $request->received_date;
        $product->expired_date = $request->expired_date;
        $product->description = $request->description;
        $product->created_by = Auth::user()->id;
        $product->save();

        $variations = $request->variations;
        foreach($variations as $variation_data){
            $variation = new Variation;
            $variation->product_id = $product->id;
            $variation->select = $variation_data['select'];
            $variation->value = $variation_data['value'];
            $variation->alert_qty = $variation_data['alert_qty'];
            $variation->item_code = $variation_data['item_code'];
            $variation->purchased_price = $variation_data['purchased_price'];
            $variation->points = $variation_data['points'];
            $variation->tickets = $variation_data['tickets'];
            $variation->kyat = $variation_data['kyat'];
            $variation_image = $variation_data['image'];
            $imagePath = $variation_image->store('variations', 'public'); 
            $variation->image = $imagePath;
            $variation->created_by = Auth::user()->id;
            $variation->save();
            
            $outlet_items = new OutletItem;
            $outlet_items->outlet_id = 1;
            $outlet_items->variation_id = $variation->id;
            $outlet_items->quantity = $variation_data['received_qty'];
            $outlet_items->created_by = Auth::user()->id;
            $outlet_items->save();
        }

       
    
        return redirect()->route('products.create')->with('success','Product create successfully');

    }

        public function get_product_lists(Request $request){
            // return $request;
        $fromOutletId = $request->fromOutletId;
        $product = Variation::select("variations.id", "products.product_name")
                    ->join("products", "variations.product_id", "=", "products.id")
                    ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
                    ->where("outlet_items.outlet_id", "=", $fromOutletId)
                    ->where("outlet_items.quantity", ">", 0)
                    ->get();

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->product_name;           
        }
        return $product_arr;
    }

    public function update_product_qty(Request $request, $id) {
        
        $DistributeProducts = DistributeProducts::find($id); 

        $input = [];
        $input['quantity'] = $request->qty;
        $input['subtotal'] = $request->qty * $DistributeProducts->purchased_price;

        return $DistributeProducts->update($input);
    }

     public function delete_dis_product($id) {
        // return $id;
        $DistributeProducts = DistributeProducts::find($id); 
        if ($DistributeProducts) {
            $result = $DistributeProducts->delete();
            return $result;
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $result = $DistributeProducts->delete();
    }

    public function get_outletdistir_product_lists(Request $request) {
       $fromOutletId = $request->fromOutletId;
        $product = Variation::select("variations.id", "products.product_name")
                    ->join("products", "variations.product_id", "=", "products.id")
                    ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
                    ->where("outlet_items.outlet_id", "=", $fromOutletId)
                    ->where("outlet_items.quantity", ">", 0)
                    ->get();
        // return $product;

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->product_name;           
        }
        return $product_arr;
    }

    public function update_outdis_product_qty(Request $request, $id) {
        
        $OutletDistributeProducts = OutletDistributeProduct::find($id); 

        $input = [];
        $input['quantity'] = $request->qty;
        $input['subtotal'] = $request->qty * $OutletDistributeProducts->purchased_price;

        return $OutletDistributeProducts->update($input);
    }

     public function delete_outletdistirbute_product($id) {
        // return $id;
        $OutletDistributeProduct = OutletDistributeProduct::find($id); 
        if ($OutletDistributeProduct) {
            $result = $OutletDistributeProduct->delete();
            return $result;
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $result = $OutletDistributeProduct->delete();
    }


    public function listProduct(){
        
        $products = Variation::with('product.brand','product.category','product.unit')->get();
        return view('products.list',compact('products'));
    }

    // public function exportProduct(){
    //     $data = Variation::with('product','outlet_item','product.brand','product.category','product.unit')->get();
    //     return Excel::download(new ProductsExport($data), 'products.xlsx');
    // }

    public function exportSampleProduct(){
        return Excel::download(new ProductsSampleExport(), 'products.xlsx');
    }

}
