<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Variation;
use App\Models\DistributeProducts;
use App\Models\OutletDistributeProduct;
use Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $html = '';
        $distributedId = $request->input('distributed_id');
        $variantId = $request->input('variant_id');
        $variant_product = Variation::find($variantId);

        $input = [];
        $input['distribute_id'] = $distributedId;
        $input['variant_id'] = $variantId;
        $input['quantity'] = 1;
        $input['purchased_price'] = $variant_product->purchased_price;
        $input['subtotal'] = $variant_product->purchased_price;
        $input['remark'] = '';
        $input['created_by'] = Auth::user()->id;

        DistributeProducts::create($input);

        $distribute_product = DistributeProducts::select('distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("distribute_id", $distributedId)->get();

        $total = 0;
        $subtotal = 0;

        if($distribute_product){
            foreach($distribute_product as $product){       
                // return $product;         
                $subtotal = $product->purchased_price * $product->quantity;
                $total += $subtotal; 
                $html .= '<table class="table table-bordered text-center shadow rounded">
                    <thead>
                        <tr>
                        <th scope="col" style="width: 30%;">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Purchased Price</th>
                        <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle" style="text-align: left;">
                                '.$product->product_name.'
                            </td>
                            <!-- <td class="align-middle"> 6Pcs + -</td> -->
                            <td class="align-middle"> 
                                <div class="qty-box border rounded">
                                    <div class="row gx-0">
                                        <div class="col">
                                            <div class="border p-2"><input type="text" class="number" min="1" value="'.$product->quantity.'"></div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseValue(this, 200)" value="Increase Value">+</div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseValue(this, 200)" value="Decrease Value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">'.$product->purchased_price.'</td>
                            <td class="align-middle">'.$subtotal.'</td>
                            <td class="align-middle"><a href="javascript:void(0)" class="text-danger" onclick="deleteDisValue('.$product->id.')">Delete</a></td>
                        </tr>
                    </tbody>
                </table>';
            }
        }

        

        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
        
        // $html = '';
        // $keyword = $request->input('keyword');
        // $products = Products::select('*')->where('product_name', 'like', "%$keyword%")
        //                     ->get();
        // if($products){
        //     return $products;
        // }
    }

    public function search_outlet_distributes (Request $request) 
    {
        $html = '';
        $distributedId = $request->outlet_distributed_id;
        $variantId = $request->variant_id;
        $variant_product = Variation::find($variantId);
        // return $distributedId;    

        $input = [];
        $input['outlet_distribute_id'] = $distributedId;
        $input['variant_id'] = $variantId;
        $input['quantity'] = 1;
        $input['purchased_price'] = $variant_product->purchased_price;
        $input['subtotal'] = $variant_product->purchased_price;
        $input['remark'] = '';
        $input['created_by'] = Auth::user()->id;

        OutletDistributeProduct::create($input);

        $outlet_distribute_product = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $distributedId)->get();
        // return $outlet_distribute_product;

        $total = 0;
        $subtotal = 0;

        if($outlet_distribute_product){
            foreach($outlet_distribute_product as $product){       
                // return $product;         
                $subtotal = $product->purchased_price * $product->quantity;
                $total += $subtotal; 
                $html .= '<table class="table table-bordered text-center shadow rounded">
                    <thead>
                        <tr>
                        <th scope="col" style="width: 30%;">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Purchased Price</th>
                        <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle" style="text-align: left;">
                                '.$product->product_name.'
                            </td>
                            <!-- <td class="align-middle"> 6Pcs + -</td> -->
                            <td class="align-middle"> 
                                <div class="qty-box border rounded">
                                    <div class="row gx-0">
                                        <div class="col">
                                            <div class="border p-2"><input type="text" class="number" min="1" value="'.$product->quantity.'"></div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this, 200)" value="Increase Value">+</div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this, 200)" value="Decrease Value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">'.$product->purchased_price.'</td>
                            <td class="align-middle">'.$subtotal.'</td>
                            <td class="align-middle"><a href="javascript:void(0)" class="text-danger" onclick="deleteOutDisValue('.$product->id.')">Delete</a></td>
                        </tr>
                    </tbody>
                </table>';
            }
        }
        
        // return $distributedId;
        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
    }

    public function search_outlet_issue (Request $request) 
    {
        // return $request;
        $html = '';
        $distributedId = $request->outlet_distributed_id;
        $variantId = $request->variant_id;
        $fromOutletId = $request->from_outlet;
        $variant_product = Variation::find($variantId);
        // return $distributedId;    

        $input = [];
        $input['outlet_distribute_id'] = $distributedId;
        $input['variant_id'] = $variantId;
        $input['quantity'] = 1;
        $input['purchased_price'] = $variant_product->purchased_price;
        $input['subtotal'] = $variant_product->purchased_price;
        $input['remark'] = '';
        $input['created_by'] = Auth::user()->id;

        OutletDistributeProduct::create($input);

        $outlet_distribute_product = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
                                ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $distributedId)->get();
        // return $outlet_distribute_product;

        $total = 0;
        $subtotal = 0;

        if($outlet_distribute_product){
            foreach($outlet_distribute_product as $product){       
                // return $product;         
                $subtotal = $product->purchased_price * $product->quantity;
                $total += $subtotal; 
                $html .= '<table class="table table-bordered text-center shadow rounded">
                    <thead>
                        <tr>
                        <th scope="col" style="width: 30%;">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Purchased Price</th>
                        <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle" style="text-align: left;">
                                '.$product->product_name.'
                            </td>
                            <!-- <td class="align-middle"> 6Pcs + -</td> -->
                            <td class="align-middle"> 
                                <div class="qty-box border rounded">
                                    <div class="row gx-0">
                                        <div class="col">
                                            <div class="border p-2"><input type="text" class="number" min="1" max=outlet_stock($variantId, $fromOutletId) value="'.$product->quantity.'"></div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this, 200)" value="Increase Value">+</div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this, 200)" value="Decrease Value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">'.$product->purchased_price.'</td>
                            <td class="align-middle">'.$subtotal.'</td>
                            <td class="align-middle"><a href="javascript:void(0)" class="text-danger" onclick="deleteOutDisValue('.$product->id.')">Delete</a></td>
                        </tr>
                    </tbody>
                </table>';
            }
        }
        
        // return $distributedId;
        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
    }
    
}
