<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Variations;
use Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'item_code' => 'required',
            'product_id' => 'required',
            'company_name' => 'required',
            'product_name' => 'required',
            'country' => 'required',
            'unit' => 'required',
            'brand' => 'required',
           
            // Add validation rules for other fields
            // ...
        ]);

        // Create a new product instance
        $product = new Products;
        $product->item_code = $validatedData['item_code'];
        $product->product_id = $validatedData['product_id'];
        $product->company_name = $validatedData['company_name'];
        $product->product_name = $validatedData['product_name'];
        $product->country = $validatedData['country'];
        $product->unit = $validatedData['unit'];
        $product->brand = $validatedData['brand'];
        // Set other product attributes

        // Save the product to the database
        $product->save();

        // Handle variations
        $variations = $request->input('variation_select');
        $purchasePrices = $request->input('purchase_price');
        $points = $request->input('points');
        $tickets = $request->input('tickets');
        $kyat = $request->input('kyat');
        // $variationImages = $request->file('variation_image');

        // Iterate over the variations and save them to the database
        for ($i = 0; $i < count($variations); $i++) {
            $variation = new Variations;
            $variation->variation_select = $variations[$i];
            $variation->purchase_price = $purchasePrices[$i];
            $variation->points = $points[$i];
            $variation->tickets = $tickets[$i];
            $variation->kyat = $kyat[$i];

            // Handle variation image upload and storage
            // if ($variationImages[$i]) {
            //     $imagePath = $variationImages[$i]->store('variation_images', 'public');
            //     $variation->image = $imagePath;
            // }

            $variation->save();
        }
        return redirect()->route('products.create')->with('succes','Brands create successfully');

    }

}
