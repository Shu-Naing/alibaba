<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Units;
use App\Models\Brands;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\DistributeProducts;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
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
        $validator = Validator::make($request->all(), [
            "product_name" => "required",
            "category_id" => "required",
            "brand_id" => "required",
            "unit_id" => "required",
            "company_name" => "required",
            "country" => "required",
            "sku" => "required",
            "received_date" => "required",
            "expired_date" => "required",
            "description" => "required",

        ]);

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
            $variation->received_qty = $variation_data['received_qty'];
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
        }
    
        return redirect()->route('products.create')->with('success','Product create successfully');

    }

    public function get_product_lists(){
        $product = Variation::select("variations.id", "products.product_name")->join("products", "variations.product_id", "=", "products.id")->get();

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


    public function show(){

        $p = new ProductsExport;
        return $p->registerEvents();
        return $p->collection();
        $products = Variation::with('product.brand','product.category','product.unit')->get();
        return view('products.show',compact('products'));
    }

    public function exportProduct(){
        $data = Variation::all();
        return Excel::download(new ProductsExport($data), 'products.xlsx');
    }

}
