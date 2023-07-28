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
use App\Models\distributes;
use App\Models\SizeVariant;
use Illuminate\Http\Request;
use App\Models\OutletItemData;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Validation\Rule;
use App\Models\DistributeProducts;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsSampleExport;
use App\Models\PurchasedPriceHistory;
use App\Models\OutletDistributeProduct;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
              ['name' => 'Products']
        ];

        // $products = Variation::with('product','product.brand','product.category','product.unit')->get();
        $products = Product::with('brand','category','unit')->get();

            // return $products;
    
        return view('products.index', compact('products', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
              ['name' => 'Products', 'url' => route('products.index')],
              ['name' => 'create']
        ];

        $brands = Brands::all();
        $categories = Categories::all();
        $units = Units::all();
        $sizeVariants = SizeVariant::all();
        return view('products.create',compact('brands','categories','units','sizeVariants', 'breadcrumbs'));
    }

        public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:products',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'company_name' => 'required',
            'country' => 'required',
            'sku' => 'required|unique:products',
            'received_date' => 'required',
            'expired_date' => 'required',
            'variations' => 'required|array',
            'variations.*.size_variant_value' => 'required',
            'variations.*.grn_no' => 'required|unique:variations',
            'variations.*.received_qty' => 'required',
            'variations.*.alert_qty' => 'required',
            'variations.*.item_code' => 'required|unique:variations',
            'variations.*.points' => 'required',
            'variations.*.image' => 'required',
            'variations.*.tickets' => 'required',
            'variations.*.kyat' => 'required',
            'variations.*.purchased_price' => 'required',
        ],

        [
            'variations.*.grn_no.unique' => 'GRN No must be unique.',
            'variations.*.size_variant_value.required' => 'The Size Variant field is required.',
            'variations.*.grn_no.required' => 'The Grn no field is required.',
            'variations.*.received_qty.required' => 'The received quantity is required.',
            'variations.*.alert_qty.required' => 'The alert quantity is required.',
            'variations.*.item_code.required' => 'The item code is required.',
            'variations.*.item_code.unique' => 'The item code must be unique.',
            'variations.*.points.required' => 'The points are required.',
            'variations.*.image.required' => 'The image is required.',
            'variations.*.tickets.required' => 'The tickets are required.',
            'variations.*.kyat.required' => 'The kyat is required.',
            'variations.*.purchased_price.required' => 'The purchased price is required.',
        ]
    );

      

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = $this->createProduct($request);

        foreach ($request->variations as $variationData) {

            $variation = $this->createVariation($product, $variationData);

            $outletItems = $this->createOutletItems($variation);

            $outletItemData = $this->createOutletItemData($variation, $outletItems, $variationData);

            $purchasedPriceHistory = $this->createPurchasedPriceHistory($variation, $variationData);
        }

        // return redirect()->route('products.create')->with('success', 'Product created successfully');

       
        return response()->json(['message' => 'Product created successfully'], 201);
    }

private function createProduct(Request $request)
{
    $product = new Product([
        'product_name' => $request->product_name,
        'unit_id' => $request->unit_id,
        'brand_id' => $request->brand_id,
        'category_id' => $request->category_id,
        'company_name' => $request->company_name,
        'country' => $request->country,
        'sku' => $request->sku,
        'received_date' => $request->received_date,
        'expired_date' => $request->expired_date,
        'description' => $request->description,
        'created_by' => Auth::user()->id,
    ]);

    $product->save();

    return $product;
}

private function createVariation(Product $product, array $variationData)
{
    $variation = new Variation([
        'product_id' => $product->id,
        'size_variant_value' => $variationData['size_variant_value'],
        'grn_no' => $variationData['grn_no'],
        'alert_qty' => $variationData['alert_qty'],
        'item_code' => $variationData['item_code'],
        'purchased_price' => $variationData['purchased_price'],
        'points' => $variationData['points'],
        'tickets' => $variationData['tickets'],
        'kyat' => $variationData['kyat'],
        'barcode' => $variationData['barcode'],
        'created_by' => Auth::user()->id,
    ]);

    $imagePath = $variationData['image']->store('variations', 'public');
    $variation->image = $imagePath;
    $variation->save();

    return $variation;
}

private function createOutletItems(Variation $variation)
{
    $outletItems = new OutletItem([
        'outlet_id' => 1,
        'variation_id' => $variation->id,
        'created_by' => Auth::user()->id,
    ]);

    $outletItems->save();

    return $outletItems;
}

private function createOutletItemData(Variation $variation,OutletItem $outletItems,array $variationData)
{
    $outletItemData = new OutletItemData([
        'outlet_item_id' =>  $outletItems->id,
        'purchased_price' => $variation->purchased_price,
        'points' => $variation->points,
        'tickets' => $variation->tickets,
        'kyat' => $variation->kyat,
        'quantity' => $variationData['received_qty'],
        'created_by' => Auth::user()->id,
    ]);

    $outletItemData->save();

    return $outletItemData;
}

private function createPurchasedPriceHistory(Variation $variation, array $variationData)
{
    $purchasedPriceHistory = new PurchasedPriceHistory([
        'variation_id' => $variation->id,
        'purchased_price' => $variation->purchased_price,
        'points' => $variation->points,
        'tickets' => $variation->tickets,
        'kyat' => $variation->kyat,
        'quantity' => $variationData['received_qty'],
        'created_by' => Auth::user()->id,
    ]);

    $purchasedPriceHistory->save();

    return $purchasedPriceHistory;
}



    public function addStock(Request $request,$variation_id){

        // return $request;
        $outlet_id = Auth::user()->outlet->id;
        Variation::find($variation_id)->update([
            'points' => $request->points,
            'tickets' => $request->tickets,
            'kyat' => $request->kyat,
            'purchased_price' => $request->purchased_price,
        ]);

        $outlet_item_id = OutletItem::where('outlet_id',$outlet_id)->where('variation_id',$variation_id)->value('id');

        OutletItemData::create([
            'outlet_item_id' => $outlet_item_id,
            'points' => $request->points,
            'tickets' => $request->tickets,
            'kyat' => $request->kyat,
            'purchased_price' => $request->purchased_price,
            'quantity' => $request->new_qty,
            'created_by' => Auth::user()->id,
        ]);

        PurchasedPriceHistory::create([
            'variation_id' => $variation_id,
            'purchased_price' => $request->purchased_price,
            'points' => $request->points,
            'tickets' => $request->tickets,
            'kyat' => $request->kyat,
            'quantity' => $request->new_qty,
            'created_by' => Auth::user()->id,
        ]);

        return response()->json(['message' => 'New Stock added successfully']);
    }


    public function edit($product_id){
        $breadcrumbs = [
              ['name' => 'products', 'url' => route('products.index')],
              ['name' => 'Edit']
        ];

        // return $product_id;
        $brands = Brands::all();
        $categories = Categories::all();
        $units = Units::all();
        $sizeVariants = SizeVariant::all();
        $product = Product::with('brand','category','unit')->find($product_id);
        $variations = Variation::whereHas('product',function ($query) use ($product_id){
            $query->where('product_id',$product_id);
        })->get();

        // return $variations;
        
        // return $product;
        return view('products.edit',compact('product','variations','brands','categories','units','sizeVariants','breadcrumbs'));
    }


    public function update(Request $request,$product_id){
        
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:products,product_name,'.$product_id,
            "category_id" => "required",
            "brand_id" => "required",
            "unit_id" => "required",
            "company_name" => "required",
            "country" => "required",
            'sku' => 'required|unique:products,sku,'.$product_id,
            "received_date" => "required",
            "expired_date" => "required",
            'variations' => 'required|array',
            'variations.*.size_variant_value' => 'required',
            'variations.*.grn_no' => 'required|unique:variations,grn_no,'.$product_id.',product_id',
            'variations.*.received_qty' => 'required',
            'variations.*.alert_qty' => 'required',
            'variations.*.item_code' => 'required|unique:variations,item_code,'.$product_id.',product_id',
            'variations.*.points' => 'required',
            'variations.*.tickets' => 'required',
            'variations.*.kyat' => 'required',
            'variations.*.purchased_price' => 'required',
        ],

        [
            'variations.*.grn_no.unique' => 'GRN No must be unique.',
            'variations.*.size_variant_value.required' => 'The Size Variant field is required.',
            'variations.*.grn_no.required' => 'The Grn no field is required.',
            'variations.*.received_qty.required' => 'The received quantity is required.',
            'variations.*.alert_qty.required' => 'The alert quantity is required.',
            'variations.*.item_code.required' => 'The item code is required.',
            'variations.*.item_code.unique' => 'The item code must be unique.',
            'variations.*.points.required' => 'The points are required.',
            'variations.*.tickets.required' => 'The tickets are required.',
            'variations.*.kyat.required' => 'The kyat is required.',
            'variations.*.purchased_price.required' => 'The purchased price is required.',
        ]
    
    );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        $productData = [
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
            'updated_by' => Auth::user()->id,
        ];
    
        Product::find($product_id)->update($productData);
    
        $variations = $request->variations;
        $outlet_id = Auth::user()->outlet->id;
    
        foreach ($variations as $variation) {
            $variationData = [
                'product_id' => $product_id,
                'item_code' =>$variation['item_code'],
                'size_variant_value' => $variation['size_variant_value'],
                'grn_no' => $variation['grn_no'],
                'points' => $variation['points'],
                'tickets' => $variation['tickets'],
                'kyat' => $variation['kyat'],
                'alert_qty' => $variation['alert_qty'],
                'purchased_price' => $variation['purchased_price'],
                'updated_by' => Auth::user()->id,
            ];
    
            if (isset($variation['image'])) {
                $variation_image = $variation['image'];
                $imagePath = $variation_image->store('variations', 'public');
                $variationData['image'] = $imagePath;
            }

            $variation_data = Variation::where('item_code', $variation['item_code'])->first();

            if(isset($variation_data)){
                $variation_data->update($variationData);
            }else{
                $variationData['created_by'] = Auth::user()->id;
                $variation_data = Variation::create($variationData);
            }
    
            // if (isset($variation['item_code'])) {
            //     $variation_data = Variation::where('item_code', $variation['item_code'])->first();
            //     $variation_data->update($variationData);
            // } else {
               
            // }
        
           
            
            $outletItem = [
                'outlet_id' => $outlet_id,
                'variation_id' => $variation_data->id,
                'updated_by' => Auth::user()->id,
            ];

            $outlet_item = OutletItem::where('outlet_id',$outlet_id)->where('variation_id',$variation_data->id)->first();
            if (isset($outlet_item)){
                // $outlet_item = OutletItem::where('outlet_id',$outlet_id)->where('variation_id',$variation_data->id)->first();
                $outlet_item->update($outletItem);
            }else{
                $outletItem['created_by'] = Auth::user()->id;
                $outlet_item = OutletItem::create($outletItem);
            }


            $outletItemData = [
                'outlet_item_id' => $outlet_item->id,
                'points' => $variation['points'],
                'tickets' => $variation['tickets'],
                'kyat' => $variation['kyat'],
                'purchased_price' => $variation['purchased_price'],
                'updated_by' => Auth::user()->id,
            ];

           $outlet_item_data = OutletItemData::where('outlet_item_id',$outlet_item->id)->first();

           if (isset($outlet_item_data)){
                // $outlet_item_data = OutletItemData::where('outlet_item_id',$outlet_item->id)->latest('created_at')->first();
                $outlet_item_data->update($outletItemData);
           }else{
            $outletItemData['quantity'] = $variation['received_qty'];
                $outletItemData['created_by'] = Auth::user()->id;
                OutletItemData::create($outletItemData);
           }


           $purchasedPriceHistory = [
                'variation_id' => $variation_data->id,
                'purchased_price' => $variation['purchased_price'],
                'points' => $variation['points'],
                'tickets' => $variation['tickets'],
                'kyat' => $variation['kyat'],
                
                'updated_by' => Auth::user()->id,
           ];


           $purchased_price_history = PurchasedPriceHistory::where('variation_id',$variation_data->id)->first();
           if (isset($purchased_price_history)){
                // $purchased_price_history = PurchasedPriceHistory::where('variation_id',$variation_data->id)->latest('created_at')->first();
                $purchased_price_history->update($purchasedPriceHistory);
           }else{
            $purchasedPriceHistory['quantity'] = $variation['received_qty'];
                $purchasedPriceHistory['created_by'] = Auth::user()->id;
                 PurchasedPriceHistory::create($purchasedPriceHistory);
           }

          
        }
       

        // return back()->with('success','Product update successfully');
        return response()->json(['message' => 'Product updated successfully'], 201);
    }

    public function get_product_lists(Request $request){
            // return $request->fromOutletId;
        $fromOutletId = $request->fromOutletId;
        $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
                    ->join("products", "variations.product_id", "=", "products.id")
                    ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
                    ->join("outlet_item_data","outlet_item_data.outlet_item_id","=","outlet_items.id")
                    ->where("outlet_items.outlet_id", "=", $fromOutletId)
                    ->where("outlet_item_data.quantity", ">", 0)
                    // ->latest("outlet_item_data.created_at")
                    ->get();

        $product_arr = array();

        foreach($product as $row){ 
            $product_arr[$row->id] = $row->item_code.' ('.$row->product_name.')';           
        }
        return $product_arr;
    }

    public function update_product_qty(Request $request, $distribute_id, $variant_id) {
        
        $distributeProducts = DistributeProducts::where('id',$distribute_id)->where('variant_id',$variant_id)->first();
        $distributeId = $distributeProducts->distribute_id;
        $oldqty = $distributeProducts->quantity;

        // $input = [];
        // $input['quantity'] = $request->qty;
        // $input['subtotal'] = $request->qty * $distributeProducts->purchased_price;

        // return $id;
        if($distributeProducts){
            $input = [];
            $input['quantity'] = $request->qty;
            $input['subtotal'] = $request->qty * $distributeProducts->purchased_price;
            $distributeProducts->update($input);

            // return $input;

            if($request->type == 'increase') {
                $distributes = distributes::where('id', $distributeId)->first();
                $toOutletId = $distributes->to_outlet;
                $fromOutletId = $distributes->from_outlet;
                
                $tooutletitem = OutletItem::where('outlet_id', $toOutletId)->where('variation_id', $variant_id)->first();
                $tooutletitem->updated_by = Auth::user()->id;
                $tooutletitem->update();

                $toOutletItemData = outlet_item_data($toOutletId,$variant_id);
                $toOutletItemData->quantity = $toOutletItemData->quantity + 1;
                $toOutletItemData->updated_by = Auth::user()->id;
                $toOutletItemData->update();




                $fromoutletitem = OutletItem::where('outlet_id', $fromOutletId)->where('variation_id', $variant_id)->first();
                $fromoutletitem->updated_by = Auth::user()->id;
                $fromoutletitem->update();

                $fromOutletItemData = outlet_item_data($fromOutletId,$variant_id);
                $fromOutletItemData->quantity = $fromOutletItemData->quantity - 1;
                $fromOutletItemData->updated_by = Auth::user()->id;
                $fromOutletItemData->update();


                return "success data";
            }else if($request->type == 'decrease') {
                $distributes = distributes::where('id', $distributeId)->first();
                $toOutletId = $distributes->to_outlet;
                $fromOutletId = $distributes->from_outlet;
                
                // $tooutletitem = OutletItem::where('outlet_id', $toOutletId)->where('variation_id', $variant_id)->first();
                // $input = [];
                // $input['quantity'] = $tooutletitem->quantity - 1;
                // $input['updated_by'] = Auth::user()->id;
                // $tooutletitem->update($input);
                $tooutletitem = OutletItem::where('outlet_id', $toOutletId)->where('variation_id', $variant_id)->first();
                $tooutletitem->updated_by = Auth::user()->id;
                $tooutletitem->update();
                
                $toOutletItemData = outlet_item_data($toOutletId,$variant_id);
                $toOutletItemData->quantity = $toOutletItemData->quantity - 1;
                $toOutletItemData->updated_by = Auth::user()->id;
                $toOutletItemData->update();

                // $fromoutletitem = OutletItem::where('outlet_id', $fromOutletId)->where('variation_id', $variant_id)->first();
                // $qty = $fromoutletitem->quantity + 1;
                // $input = [];
                // $input['quantity'] = $qty;
                // $fromoutletitem->update($input);
                $fromoutletitem = OutletItem::where('outlet_id', $fromOutletId)->where('variation_id', $variant_id)->first();
                $fromoutletitem->updated_by = Auth::user()->id;
                $fromoutletitem->update();

                $fromOutletItemData = outlet_item_data($fromOutletId,$variant_id);
                $fromOutletItemData->quantity = $fromOutletItemData->quantity + 1;
                $fromOutletItemData->updated_by = Auth::user()->id;
                $fromOutletItemData->update();


                return "success data";
            }else {
                $inputqty = $request->qty;
                $distributes = distributes::where('id', $distributeId)->first();
                $toOutletId = $distributes->to_outlet;
                $fromOutletId = $distributes->from_outlet;
                // return  $oldqty;
                
                // $tooutletitem = OutletItem::where('outlet_id', $toOutletId)->where('variation_id', $variant_id)->first();
                // $input = [];
                // $input['quantity'] = ($tooutletitem->quantity - $oldqty) + $inputqty;
                // $input['updated_by'] = Auth::user()->id;
                // $tooutletitem->update($input);

                $tooutletitem = OutletItem::where('outlet_id', $toOutletId)->where('variation_id', $variant_id)->first();
                $tooutletitem->updated_by = Auth::user()->id;
                $tooutletitem->update();

                $toOutletItemData = outlet_item_data($toOutletId,$variant_id);
                $toOutletItemData->quantity = ($toOutletItemData->quantity - $oldqty) + $inputqty ;
                $toOutletItemData->updated_by = Auth::user()->id;
                $toOutletItemData->update();
               

                // $fromoutletitem = OutletItem::where('outlet_id', $fromOutletId)->where('variation_id', $variant_id)->first();
                // $qty = ($fromoutletitem->quantity + $oldqty) - $inputqty;
                // $input = [];
                // $input['quantity'] = $qty;
                // $fromoutletitem->update($input);
                $fromoutletitem = OutletItem::where('outlet_id', $fromOutletId)->where('variation_id', $variant_id)->first();
                $fromoutletitem->updated_by = Auth::user()->id;
                $fromoutletitem->update();

                $fromOutletItemData = outlet_item_data($fromOutletId,$variant_id);
                $fromOutletItemData->quantity = ($fromOutletItemData->quantity + $oldqty) - $inputqty ;
                $fromOutletItemData->updated_by = Auth::user()->id;
                $fromOutletItemData->update();


                // $rn = [];
                // $rn['tooutlet'] = $tooutletitem;
                // $rn['fromoutlet'] = $input;
                // return $rn;

                return "success data";
            }
            
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

    // public function get_outletdistir_product_lists(Request $request) {
    //    $fromOutletId = $request->fromOutletId;
    //     $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
    //                 ->join("products", "variations.product_id", "=", "products.id")
    //                 ->join("outlet_items", "outlet_items.variation_id", "=", "variations.id")
    //                 ->where("outlet_items.outlet_id", "=", $fromOutletId)
    //                 ->where("outlet_items.quantity", ">", 0)
    //                 ->get();
    //     // return $product;

    //     $product_arr = array();

    //     foreach($product as $row){ 
    //         $product_arr[$row->id] = $row->item_code .' ('.$row->product_name.')';           
    //     }
    //     return $product_arr;
    // }

    public function get_outletdistir_issue_lists(Request $request) {
        $to_machine = $request->to_machine;
        $product = Variation::select("variations.id", "products.product_name", "variations.item_code")
                     ->join("products", "variations.product_id", "=", "products.id")
                     ->join("machine_variants", "machine_variants.variant_id", "=", "variations.id")
                     ->where("machine_variants.machine_id", "=", $to_machine)
                     ->where("machine_variants.quantity", ">", 0)
                     ->get();
        //  return $product;
 
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

        $images = $request->file('images');

        foreach ($images as $image) {
           
            $imageName = $image->getClientOriginalName();
            $image->storeAs('public/variations', $imageName);
        }

        Excel::import(new ProductsImport, $file);

        return redirect()->back()->with('success', 'Products imported successfully.');
    }

    

   

}
