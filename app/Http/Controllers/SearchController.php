<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Products;
use App\Models\Variation;
use App\Models\OutletItem;
use App\Models\distributes;
use Illuminate\Http\Request;
use App\Models\MachineVariant;
use App\Models\OutletItemData;
use App\Models\DistributeProducts;
use App\Models\OutletDistributeProduct;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        
        $html = '';
       
        $variantId = $request->variant_id;
        $fromOutletId = $request->from_outlet;
        $variant_product = Variation::join('products', 'products.id', '=', 'variations.product_id')->where('variations.id', $variantId)->first();
        // return $variant_product;

        // $outletItem = OutletItem::select('quantity')
        // ->where('outlet_id', $fromOutletId)
        // ->where('variation_id', $variantId)
        // ->first();
        $fromOutletItemData = outlet_item_data($fromOutletId,$variantId);

        // if($outletItem) {
        //     $variant_qty = $outletItem->quantity;
        // }

        $variant_qty = $fromOutletItemData->quantity;

      
        $total = 0;
        $subtotal = 0;

             
                $subtotal = $fromOutletItemData->purchased_price;
                $total += $subtotal; 
                $html .= '
                        <tr data-id="'.$variantId.'">
                            <td class="align-middle" style="text-align: left;">
                                '.$variant_product->product_name.'
                            </td>
                            <td class="align-middle" style="text-align: left;">
                                '.$variant_product->item_code.'
                            </td>
                            <!-- <td class="align-middle"> 6Pcs + -</td> -->
                            <td class="align-middle"> 
                                <div class="qty-box border rounded">
                                    <div class="row gx-0">
                                        <div class="col">
                                            <div class="border p-2"><input type="text" name="'.$variant_product->item_code.'_qtyNumber" class="number number-box number-qty" id="quantity-num" min="1" value="1" data-id="['.$fromOutletItemData->purchased_price.','.$variant_qty.']"></div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseValue(this, '.$fromOutletItemData->purchased_price.','.$variant_qty.')" value="Increase Value">+</div>
                                        </div>
                                        <div class="col">
                                            <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseValue(this,'.$fromOutletItemData->purchased_price.','.$variant_qty.')" value="Decrease Value">-</div>
                                        </div>  
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">'.$fromOutletItemData->purchased_price.'</td>
                            <td class="align-middle subtotal">'.$subtotal.'</td>
                            <td class="align-middle"><a href="javascript:void(0)" onclick="deleteDisValue(this)" class="text-danger deleteBox">Delete</a></td>
                        </tr>';
      
        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
       
    }

    public function search_outlet_distributes (Request $request) 
    {

        $html = '';
        $variant_qty = 0;
        $distributedId = $request->outlet_distributed_id;
        $fromOutletId = $request->from_outlet;
        $variantId = $request->variant_id;
        $variant_product = Variation::find($variantId);
        // return $distributedId;    

        $outletItem = OutletItem::select('quantity')
        ->where('outlet_id', $fromOutletId)
        ->where('variation_id', $variantId)
        ->first();

        if($outletItem) {
            $variant_qty = $outletItem->quantity;
        }

        $input = [];
        $input['outlet_distribute_id'] = $distributedId;
        $input['variant_id'] = $variantId;
        $input['purchased_price'] = $variant_product->purchased_price;
        $input['subtotal'] = $variant_product->purchased_price;
        $input['remark'] = '';
        $input['created_by'] = Auth::user()->id;

        #don't allow to add same product in search product ( recieve/issue/distribute)
        #if added same product , just increse quantiy 
        $outletdistributeproduct = OutletDistributeProduct::where('outlet_distribute_id',$distributedId)
        ->where('variant_id',$variantId)
        ->first();        
        if($outletdistributeproduct){
            if($outletdistributeproduct->quantity + 1 > $variant_qty){
                $input['quantity'] = $outletdistributeproduct->quantity;
            }else{
                $input['quantity'] = $outletdistributeproduct->quantity + 1;
            }            
            $outletdistributeproduct->update($input);
        }else{
            $input['quantity'] = 1;
            OutletDistributeProduct::create($input);
        }

        // $outlet_distribute_product = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
        //                         ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $distributedId)->get();
        // return $outlet_distribute_product;

        // $total = 0;
        // $subtotal = 0;

        // if($outlet_distribute_product){
        //     foreach($outlet_distribute_product as $product){       
        //         // return $product;         
        //         $subtotal = $product->purchased_price * $product->quantity;
        //         $total += $subtotal; 
        //         $html .= '<table class="table table-bordered text-center shadow rounded">
        //             <thead>
        //                 <tr>
        //                 <th scope="col" style="width: 30%;">Product Name</th>
        //                 <th scope="col">Quantity</th>
        //                 <th scope="col">Purchased Price</th>
        //                 <th scope="col">Subtotal</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <tr>
        //                     <td class="align-middle" style="text-align: left;">
        //                         '.$product->product_name.'
        //                     </td>
        //                     <!-- <td class="align-middle"> 6Pcs + -</td> -->
        //                     <td class="align-middle"> 
        //                         <div class="qty-box border rounded">
        //                             <div class="row gx-0">
        //                                 <div class="col">
        //                                     <div class="border p-2"><input type="text" class="number" min="1" value="'.$product->quantity.'"></div>
        //                                 </div>
        //                                 <div class="col">
        //                                     <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this, '.$product->id.','.$variantId.','.$variant_qty.')" value="Increase Value">+</div>
        //                                 </div>
        //                                 <div class="col">
        //                                     <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this, '.$product->id.','.$variantId.')" value="Decrease Value">-</div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </td>
        //                     <td class="align-middle">'.$product->purchased_price.'</td>
        //                     <td class="align-middle">'.$subtotal.'</td>
        //                     <td class="align-middle"><a href="javascript:void(0)" class="text-danger" onclick="deleteOutDisValue('.$product->id.')">Delete</a></td>
        //                 </tr>
        //             </tbody>
        //         </table>';
        //     }
        // }
        
        // return $distributedId;
        // $response = array();
        // $response['total'] = $total;
        // $response['html'] = $html;

        // return json_encode($response);
        return "success data";
    }

    public function search_outlet_issue (Request $request) 
    {
        // return $request;
        $html = '';
        $variant_qty = 0;
        $distributedId = $request->outlet_distributed_id;
        $variantId = $request->variant_id;
        # $fromOutletId = $request->from_outlet;
        # for issue , we have to check with quantity of to_machine, cause variant is issued from machine
        $to_machine = $request->to_machine;
        
        $variant_product = Variation::find($variantId);
        // return $distributedId;    

        // $outletItem = OutletItem::select('quantity')
        // ->where('outlet_id', $fromOutletId)
        // ->where('variation_id', $variantId)
        // ->first();
        #get machine variant quantity
        $machineVariant = MachineVariant::where('machine_id',$to_machine)
        ->where('variant_id',$variantId)
        ->first();

        if($machineVariant) {
            $variant_qty = $machineVariant->quantity;
        }

        $input = [];
        $input['outlet_distribute_id'] = $distributedId;
        $input['variant_id'] = $variantId;
        $input['quantity'] = 1;
        $input['purchased_price'] = $variant_product->purchased_price;
        $input['subtotal'] = $variant_product->purchased_price;
        $input['remark'] = '';
        $input['created_by'] = Auth::user()->id;

        #don't allow to add same product in search product ( recieve/issue/distribute)
        #if added same product , just increse quantiy 
        $outletdistributeproduct = OutletDistributeProduct::select('quantity')
        ->where('outlet_distribute_id',$distributedId)
        ->where('variant_id',$variantId)
        ->first();
        if($outletdistributeproduct){
            if($outletdistributeproduct->quantity + 1 > $variant_qty){
                $input['quantity'] = $outletdistributeproduct->quantity;
            }else{
                $input['quantity'] = $outletdistributeproduct->quantity + 1;
            }            
            $outletdistributeproduct->update($input);
        }else{
            $input['quantity'] = 1;
            OutletDistributeProduct::create($input);
        }
        // $outlet_distribute_product = OutletDistributeProduct::select('outlet_distribute_products.*','products.product_name')->join("variations", "variations.id", "=", "outlet_distribute_products.variant_id")
        //                         ->join("products", "products.id", "=", "variations.product_id")->where("outlet_distribute_id", $distributedId)->get();
        // return $outlet_distribute_product;

        // $total = 0;
        // $subtotal = 0;

        // if($outlet_distribute_product){
        //     foreach($outlet_distribute_product as $product){       
        //         // return $product;         
        //         $subtotal = $product->purchased_price * $product->quantity;
        //         $total += $subtotal; 
        //         $html .= '<table class="table table-bordered text-center shadow rounded">
        //             <thead>
        //                 <tr>
        //                 <th scope="col" style="width: 30%;">Product Name</th>
        //                 <th scope="col">Quantity</th>
        //                 <th scope="col">Purchased Price</th>
        //                 <th scope="col">Subtotal</th>
        //                 </tr>
        //             </thead>
        //             <tbody>
        //                 <tr>
        //                     <td class="align-middle" style="text-align: left;">
        //                         '.$product->product_name.'
        //                     </td>
        //                     <!-- <td class="align-middle"> 6Pcs + -</td> -->
        //                     <td class="align-middle"> 
        //                         <div class="qty-box border rounded">
        //                             <div class="row gx-0">
        //                                 <div class="col">
        //                                     <div class="border p-2"><input type="text" class="number" min="1" max=outlet_stock($variantId, $fromOutletId) value="'.$product->quantity.'"></div>
        //                                 </div>
        //                                 <div class="col">
        //                                     <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this, '.$product->id.','.$variantId.','.$variant_qty.')" value="Increase Value">+</div>
        //                                 </div>
        //                                 <div class="col">
        //                                     <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this, '.$product->id.','.$variantId.')" value="Decrease Value">-</div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </td>
        //                     <td class="align-middle">'.$product->purchased_price.'</td>
        //                     <td class="align-middle">'.$subtotal.'</td>
        //                     <td class="align-middle"><a href="javascript:void(0)" class="text-danger" onclick="deleteOutDisValue('.$product->id.')">Delete</a></td>
        //                 </tr>
        //             </tbody>
        //         </table>';
        //     }
        // }
        
        // return $distributedId;
        // $response = array();
        // $response['total'] = $total;
        // $response['html'] = $html;

        // return json_encode($response);
        return "success data";
    }

    public function search_list_distribute_detail(Request $request) {
       
        session()->start();
        session()->put(PD_FROMOUTLET_FILTER, $request->fromOutlet);
        session()->put(PD_TOOUTLET_FILTER, $request->toOutlet);
        session()->put(PD_ITEMCODE_FILTER, $request->itemCode);
        
        return redirect()->route('listdistributedetail');
    }

    public function search_reset() {
        session()->forget([
            PD_FROMOUTLET_FILTER,
            PD_TOOUTLET_FILTER,
            PD_ITEMCODE_FILTER,
        ]);
        return redirect()->route('listdistributedetail');
    }
    
}
