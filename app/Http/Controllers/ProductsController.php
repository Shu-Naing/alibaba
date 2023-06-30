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
use App\Imports\ProductsImport;
use Illuminate\Validation\Rule;
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
        // $products = Variation::with('product','product.brand','product.category','product.unit')->get();
        $products = Product::with('brand','category','unit')->get();

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
            // "description" => "required",
            'variations' => 'required|array',
            'variations.*.select' => 'required',
            'variations.*.value' => 'required',
            'variations.*.received_qty' => 'required',
            'variations.*.alert_qty' => 'required',
            'variations.*.item_code' => 'required|unique:variations',
            'variations.*.points' => 'required',
            'variations.*.image' => 'required',
            'variations.*.tickets' => 'required',
            'variations.*.kyat' => 'required',
            'variations.*.purchased_price' => 'required',

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

    public function edit($product_id){

        // return $product_id;
        $brands = Brands::all();
        $categories = Categories::all();
        $units = Units::all();
        $product = Product::with('brand','category','unit')->find($product_id);
        $variations = Variation::whereHas('product',function ($query) use ($product_id){
            $query->where('product_id',$product_id);
        })->get();
        
        // return $product;
        return view('products.edit',compact('product','variations','brands','categories','units'));
    }


    public function update(Request $request,$product_id){
        
        $validator = Validator::make($request->all(), [
            "product_name" => [
                'required',
                Rule::unique('products')->ignore($product_id),
            ],
            "category_id" => "required",
            "brand_id" => "required",
            "unit_id" => "required",
            "company_name" => "required",
            "country" => "required",
            "sku" => [
                'required',
                Rule::unique('products')->ignore($product_id),
            ],
            "received_date" => "required",
            "expired_date" => "required",
            'variations' => 'required|array',
            'variations.*.select' => 'required',
            'variations.*.value' => 'required',
            'variations.*.received_qty' => 'required',
            'variations.*.alert_qty' => 'required',
            'variations.*.item_code' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $existingCodes = collect($request->input('variations'))->pluck('item_code')->filter();
                    if ($existingCodes->count() !== $existingCodes->unique()->count()) {
                        $fail('Duplicate item codes found.');
                    }
                },
            ],
            'variations.*.points' => 'required',
            'variations.*.tickets' => 'required',
            'variations.*.kyat' => 'required',
            'variations.*.purchased_price' => 'required',

        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }
        Product::find($product_id)->update([

            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'unit_id' => $request->unit_id,
            'company_name' => $request->company_name,
            'country' => $request->country,
            'sku' => $request->sku,
            'received_date' => $request->received_date,
            'expired_date' => $request->expired_date,
            'description' => $request->description,

        ]);


        $variations = $request->variations; 
        $outlet_id = Auth::user()->outlet->id;

        // return $request->variations[0]['image'];

        foreach($variations as $variation) {

            

            $variationData = [
                'product_id' => $product_id,
                'select' => $variation['select'] ,
                'value' => $variation['value'] ,
                'points' => $variation['points'] , 
                'tickets' => $variation['tickets'],
                'kyat'=> $variation['kyat'], 
                'alert_qty' => $variation['alert_qty'],
                'purchased_price' => $variation['purchased_price'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];

            if (isset($variation['image'])) {
                $variation_image = $variation['image'];
                $imagePath = $variation_image->store('variations', 'public'); 
                $variationData['image'] = $imagePath;
            }
        
            $variation_data = Variation::updateOrCreate(['item_code' => $variation['item_code']], $variationData);

            OutletItem::where('outlet_id',$outlet_id)->where('variation_id',$variation_data->id)->update([
                'quantity' => $variation['received_qty']
            ]);

        }
       

        return back()->with('success','Product update successfully');
    }

        public function get_product_lists(Request $request){
            // return $request;
        $fromOutletId = $request->fromOutletId;
        $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
                    ->join("products", "variations.product_id", "=", "products.id")
                    ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
                    ->where("outlet_items.outlet_id", "=", $fromOutletId)
                    ->where("outlet_items.quantity", ">", 0)
                    ->get();

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->item_code.' ('.$row->product_name.')';           
        }
        return $product_arr;
    }

    public function update_product_qty(Request $request, $distribute_id, $variant_id) {
        
        $distributeProducts = DistributeProducts::where('id',$distribute_id)->where('variant_id',$variant_id)->first();

        $input = [];
        $input['quantity'] = $request->qty;
        $input['subtotal'] = $request->qty * $distributeProducts->purchased_price;

        $arr = [];
        $arr['request'] = $request->all();
        $arr['distributeproduct'] = $distributeProducts;
        $arr['input'] = $input;

        // return $id;
        if($distributeProducts){
            $input = [];
            $input['quantity'] = $request->qty;
            $input['subtotal'] = $request->qty * $distributeProducts->purchased_price;
            return $distributeProducts->update($input);
        }else{
            return 'distribute product does not found'. $id;
        }
    }

     public function delete_dis_product($id) {
        // return $id;
        $distributeProducts = DistributeProducts::find($id); 
        if ($distributeProducts) {
            $result = $distributeProducts->delete();
            return $result;
        } else {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $result = $distributeProducts->delete();
    }

    public function get_outletdistir_product_lists(Request $request) {
       $fromOutletId = $request->fromOutletId;
        $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
                    ->join("products", "variations.product_id", "=", "products.id")
                    ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
                    ->where("outlet_items.outlet_id", "=", $fromOutletId)
                    ->where("outlet_items.quantity", ">", 0)
                    ->get();
        // return $product;

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->item_code .' ('.$row->product_name.')';           
        }
        return $product_arr;
    }

    public function get_outletdistir_issue_lists(Request $request) {
        $to_machine = $request->to_machine;
        $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
                     ->join("products", "variations.product_id", "=", "products.id")
                     ->join("machine_variants", "machine_variants.variant_id", "=", "variations.id")
                     ->where("machine_variants.machine_id", "=", $to_machine)
                     ->where("machine_variants.quantity", ">", 0)
                     ->get();
         // return $product;
 
         $product_arr = array();
 
         foreach($product as $row){ 
             $product_arr[$row->id] = $row->item_code .' ('.$row->product_name.')';           
         }
         return $product_arr;
     }

    public function update_outdis_product_qty(Request $request, $outlet_distribute_id, $variant_id) {
        
        $OutletDistributeProducts = OutletDistributeProduct::where('id',$outlet_distribute_id)->where('variant_id',$variant_id)->first();
        
        // $OutletDistributeProducts = OutletDistributeProduct::find($id); 

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

    public function importProduct(Request $request){


        // return $request;
        $file = $request->file('file');

        Excel::import(new ProductsImport, $file);

        return redirect()->back()->with('success', 'Products imported successfully.');
    }

}
