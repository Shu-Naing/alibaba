<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Units;
use App\Models\Brands;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Categories;
use Illuminate\Http\Request;

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
    //    return $request->variations[0]['variation_select'];
        // Validate the form data
        // $validatedData = $request->validate([
        //     'item_code' => 'required',
        //     'company_name' => 'required',
        //     'product_name' => 'required',
        //     'sku' =?
        //     'country' => 'required',
        //     'unit' => 'required',
        //     'brand' => 'required',
           
        // ]);

        // Create a new product instance
        $product = new Product;
        $product->item_code = $request->item_code;
        $product->company_name = $request->company_name;
        $product->product_name = $request->product_name;
        $product->sku = $request->sku;
        $product->country = $request->country;
        $product->unit_id = $request->unit_id;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->received_date = $request->received_date;
        $product->quantity = $request->quantity;
        $product->received_qty = $request->received_qty;
        $product->expired_date = $request->expired_date;
        $product->description = $request->description;
        $product->created_by = Auth::user()->id;
        if (isset($request->image)) {
            $imagePath = $request->image->store('products', 'public'); // Store the image
            $product->image = $imagePath;
           
        }
        $product->save();

        $variations = $request->variations;


        foreach($variations as $variation_data){
            $variation = new Variation;
            $variation->product_id = $product->id;
            $variation->variation_select = $variation_data['variation_select'];
            $variation->variation_value = $variation_data['variation_value'];
            $variation->purchased_price = $variation_data['purchased_price'];
            $variation->points = $variation_data['points'];
            $variation->tickets = $variation_data['tickets'];
            $variation->kyat = $variation_data['kyat'];
            $variation->created_by = Auth::user()->id;

            if (isset($variation_data['variation_image'])) {
                $imagePaths = [];
                foreach ($variation_data['variation_image'] as $variation_image) {
                    $imagePath = $variation_image->store('variations', 'public'); // Store the image
                    $imagePaths[] =  $imagePath;
                }
                $variation->variation_image = json_encode($imagePaths);
            }

            $variation->save();
        }
    
        return redirect()->route('products.create')->with('success','Product create successfully');

    }

}
