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
        $variant_product = Variation::join('products', 'products.id', '=', 'variations.product_id')
        ->where('variations.id', $variantId)->first();
        // return $variant_product;

        // $outletItem = OutletItem::select('quantity')
        // ->where('outlet_id', $fromOutletId)
        // ->where('variation_id', $variantId)
        // ->first();
        $fromOutletItemData = outlet_item_data($fromOutletId,$variantId);

        if($fromOutletItemData) {
            $variant_qty = $fromOutletItemData->quantity;
        }

      
        $total = 0;
        $subtotal = 0;

             
                $subtotal = $fromOutletItemData->purchased_price;
                $total += $subtotal; 
                $html .= '
                        <tr data-id="'.$variantId.'" class="hello">
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
                            <td class="align-middle">'.number_format($fromOutletItemData->purchased_price, 0, '', ',').'</td>
                            <td class="align-middle subtotal">'.number_format($subtotal, 0, '', ',').'</td>
                            <td class="align-middle"><a href="javascript:void(0)" onclick="deleteDisValue(this)" class="text-danger deleteBox">Delete</a></td>
                        </tr>';
      
        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
       
    }

    public function search_outlet_issue (Request $request) 
    {
        // return $request; 
        $html = '';
        $variant_qty = 0;
        $variantId = $request->variant_id;
        $to_machine = $request->to_machine;
        
        $variant_product = Variation::join('products', 'products.id', '=', 'variations.product_id')->where('variations.id', $variantId)->first();
        
        #get machine variant quantity
        $machineVariant = MachineVariant::where('machine_id',$to_machine)
        ->where('variant_id',$variantId)
        ->first();

        if($machineVariant) {
            $variant_qty = $machineVariant->quantity;
        }

        $total = 0;
        $subtotal = 0;

        $subtotal = $variant_product->purchased_price;
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
                                    <div class="border p-2"><input type="text" name="'.$variant_product->item_code.'_qtyNumber" class="number number-box number-qty" id="quantity-num" min="1" value="1" data-id="['.$variant_product->purchased_price.','.$variant_qty.']"></div>
                                </div>
                                <div class="col">
                                    <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseValue(this, '.$variant_product->purchased_price.','.$variant_qty.')" value="Increase Value">+</div>
                                </div>
                                <div class="col">
                                    <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseValue(this,'.$variant_product->purchased_price.','.$variant_qty.')" value="Decrease Value">-</div>
                                </div>  
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">'.$variant_product->purchased_price.'</td>
                    <td class="align-middle subtotal">'.$subtotal.'</td>
                    <td class="align-middle"><a href="javascript:void(0)" onclick="deleteDisValue(this)" class="text-danger deleteBox">Delete</a></td>
                </tr>
        ';
        
        $response = array();
        $response['total'] = $total;
        $response['html'] = $html;

        return json_encode($response);
        // return "success data";
    }

    public function search_damage (Request $request) 
    {
        // return "hello";
        $html = '';
        $variant_qty = 0;
        $variantId = $request->variant_id;
        $outlet = $request->outlet;
        
        $variant_product = OutletItemData::join('outlet_items', 'outlet_items.id', '=', 'outlet_item_data.outlet_item_id')
        ->join('variations', 'variations.id', 'outlet_items.variation_id')
        ->join('products', 'products.id', 'variations.product_id')
        ->where('variations.id', $variantId)
        ->where('outlet_items.outlet_id', $outlet)
        ->orderBy('outlet_item_data.created_at','desc')->first();

        $total = $variant_product->purchased_price;

        $html .= '
                <tr data-id="'.$variantId.'">
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->product_name.'
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->item_code.'
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->points.'
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->tickets.'
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->kyat.'
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        '.$variant_product->purchased_price.'
                    </td>
                    <td class="align-middle">
                       <input type="number" name="quantity['.$variant_product->variation_id.']" class="form-control damageQuantity" min="1" value="1">
                    </td>
                    <td class="align-middle damageTotal">'.$total.'</td>
                    <td class="align-middle">
                        <input type="textarea" name="reason['.$variant_product->variation_id.']" value="" row="2" class="form-control" id="">
                        <input type="hidden" name="variation_id[]" value="'.$variant_product->variation_id.'" row="2" class="" id="">
                        <input type="hidden" name="item_code['.$variant_product->variation_id.']" value="'.$variant_product->item_code.'" class="" id="">
                        <input type="hidden" name="points['.$variant_product->variation_id.']" value="'.$variant_product->points.'" class="" id="">
                        <input type="hidden" name="tickets['.$variant_product->variation_id.']" value="'.$variant_product->tickets.'" class="" id="">
                        <input type="hidden" name="kyat['.$variant_product->variation_id.']" value="'.$variant_product->kyat.'" class="" id="">
                        <input type="hidden" name="purchase_price['.$variant_product->variation_id.']" value="'.$variant_product->purchased_price.'" class="damagePrice">
                        <input type="hidden" name="total['.$variant_product->variation_id.']" value="'.$total.'" class="damageTotal">
                    </td>
                    <td class="align-middle"><a href="javascript:void(0)" onclick="deleteDisValue(this)" class="text-danger deleteBox">Delete</a></td>
                </tr>
        ';
        
        return $html;
    }

    public function search_list_distribute_detail(Request $request) {
       
        session()->start();
        session()->put(PD_FROMOUTLET_FILTER, $request->fromOutlet);
        session()->put(PD_TOOUTLET_FILTER, $request->toOutlet);
        session()->put(PD_ITEMCODE_FILTER, $request->itemCode);
        session()->put(PD_FROMDATE_FILTER, $request->fromDate);
        session()->put(PD_TODATE_FILTER, $request->toDate);
        session()->put(PD_SIZEVARIANT_FILTER, $request->sizeVariant);
        session()->put(PD_PURCHASEPRICE_FILTER, $request->purchasePrice);
        session()->put(PD_VOUNCHERNO_FILTER, $request->vouncherNo);
        
        return redirect()->route('listdistributedetail');
    }

    public function search_reset() {
        session()->forget([
            PD_FROMOUTLET_FILTER,
            PD_TOOUTLET_FILTER,
            PD_ITEMCODE_FILTER,
            PD_DATE_FILTER,
            PD_FROMDATE_FILTER,
            PD_TODATE_FILTER,
            PD_SIZEVARIANT_FILTER,
            PD_PURCHASEPRICE_FILTER,
            PD_VOUNCHERNO_FILTER,
            DA_FROMDATE_FILTER,
            DA_TODATE_FILTER,
            DA_DAMAGE_FILTER,
            DA_OUTLETID_FILTER,
            DA_ITEMCODE_FILTER,
        ]);
        return redirect()->route('listdistributedetail');
    }

    public function search_list_damage(Request $request) {
    //    return $request;
        session()->start();
        session()->put(DA_FROMDATE_FILTER, $request->fromDate);
        session()->put(DA_TODATE_FILTER, $request->toDate);
        session()->put(DA_DAMAGE_FILTER, $request->damage_no);
        session()->put(DA_OUTLETID_FILTER, $request->outletId);
        // session()->put(DA_ITEMCODE_FILTER, $request->itemCode);
        return redirect()->route('damage.index');
    }

    public function search_bodanddepartment(Request $request) {
       
        session()->start();
        session()->put(PD_FROMOUTLET_FILTER, $request->fromOutlet);
        session()->put(PD_TOOUTLET_FILTER, $request->toOutlet);
        session()->put(PD_ITEMCODE_FILTER, $request->itemCode);
        session()->put(PD_FROMDATE_FILTER, $request->fromDate);
        session()->put(PD_TODATE_FILTER, $request->toDate);
        session()->put(PD_SIZEVARIANT_FILTER, $request->sizeVariant);
        session()->put(PD_PURCHASEPRICE_FILTER, $request->purchasePrice);
        session()->put(PD_VOUNCHERNO_FILTER, $request->vouncherNo);
        
        return redirect()->route('report.bodanddepartment');
    }

    public function bodanddepartment_reset() {
        session()->forget([
            PD_FROMOUTLET_FILTER,
            PD_TOOUTLET_FILTER,
            PD_ITEMCODE_FILTER,
            PD_DATE_FILTER,
            PD_FROMDATE_FILTER,
            PD_TODATE_FILTER,
            PD_SIZEVARIANT_FILTER,
            PD_PURCHASEPRICE_FILTER,
            PD_VOUNCHERNO_FILTER,
        ]);
        return redirect()->route('report.bodanddepartment');
    }

    public function damage_search_reset() {
        session()->forget([
            DA_FROMDATE_FILTER,
            DA_TODATE_FILTER,
            DA_DAMAGE_FILTER,
            DA_OUTLETID_FILTER,
            DA_ITEMCODE_FILTER,
        ]);
        return redirect()->route('damage.index');
    }

    public function search_list_adjustment(Request $request) {
    //    return $request;
        session()->start();
        session()->put(ADJ_FROMDATE_FILTER, $request->fromDate);
        session()->put(ADJ_TODATE_FILTER, $request->toDate);
        session()->put(ADJ_ADJNO_FILTER, $request->adjNo);
        session()->put(ADJ_OUTLETID_FILTER, $request->outletId);
        session()->put(ADJ_ITEMCODE_FILTER, $request->itemCode);
        return redirect()->route('adjustment.index');
    }

    public function adjustment_search_reset() {
        session()->forget([
            ADJ_FROMDATE_FILTER,
            ADJ_TODATE_FILTER,
            ADJ_ADJNO_FILTER,
            ADJ_OUTLETID_FILTER,
            ADJ_ITEMCODE_FILTER,
        ]);
        return redirect()->route('adjustment.index');
    }

    public function search_purchase_detail(Request $request) {
    //    return $request;
        session()->start();
        session()->put(PURCHASE_ITEMCODE_FILTER, $request->itemCode);
        return redirect()->back();
    }

    public function purchase_search_reset() {
        session()->forget([
            PURCHASE_ITEMCODE_FILTER,
        ]);
        return redirect()->back();
    }

    public function searchProduct(Request $request) {
        // return $request;
        session()->start();
        session()->put('PD_RECEIVED_DATE_FILTER', $request->received_date);
        
        return redirect()->route('report.products');
    }

    public function resetProduct(){
        session()->forget('PD_RECEIVED_DATE_FILTER');
        return redirect()->route('report.products');
    }
    
    public function outletlevelhistorySearch(Request $request){
        // return $request;
        session()->start();
        session()->put('OUTLET_LEVEL_HISTORY_FILTER', $request->outlet_id);
        session()->put('OUTLET_LEVEL_HISTORY_FROM_DATE_FILTER', $request->fromDate);
        session()->put('OUTLET_LEVEL_HISTORY_TO_DATE_FILTER', $request->toDate); 
        return redirect()->route('outletlevelhistory.index');
    }

    public function resetOutletlevelhistory(){
        session()->forget('OUTLET_LEVEL_HISTORY_FILTER');
        session()->forget('OUTLET_LEVEL_HISTORY_FROM_DATE_FILTER');
        session()->forget('OUTLET_LEVEL_HISTORY_TO_DATE_FILTER');
        return redirect()->route('outletlevelhistory.index');
    }

    public function outletlevelverviewSearch(Request $request){
        // return $request;
        session()->start();
        session()->put('OUTLET_LEVEL_OVERVIEW_FILTER', $request->outlet_id);
        session()->put('OUTLET_LEVEL_OVERVIEW_FROM_DATE_FILTER', $request->fromDate);
        session()->put('OUTLET_LEVEL_OVERVIEW_TO_DATE_FILTER', $request->toDate);
        return redirect()->route('outletleveloverview.index');
    }

    public function resetOutletleveloverview(){
        session()->forget('OUTLET_LEVEL_OVERVIEW_FILTER');
        session()->forget('OUTLET_LEVEL_OVERVIEW_FROM_DATE_FILTER');
        session()->forget('OUTLET_LEVEL_OVERVIEW_TO_DATE_FILTER');
        return redirect()->route('outletleveloverview.index');
    }

    public function search_purchase(Request $request){
        $html = '';
        $variantId = $request->variant_id;

        $outletItemData = OutletItemData::select('outlet_item_data.points','outlet_item_data.tickets','outlet_item_data.kyat','outlet_item_data.purchased_price','variations.item_code')->join('outlet_items', 'outlet_items.id', '=', 'outlet_item_data.outlet_item_id')
        ->join('variations', 'variations.id', '=', 'outlet_items.variation_id')
        ->where('outlet_items.outlet_id',MAINOUTLETID)
        ->where('outlet_items.variation_id', $variantId)
        ->orderBy('outlet_item_data.id','desc')->first();

      

        $total = $outletItemData->purchased_price * $outletItemData->quantity;

        $html .= '
                <tr data-id="'.$variantId.'">
                    <td class="align-middle" style="text-align: left;">
                        '.$outletItemData->item_code.'
                        <input type="hidden" class="form-control" name="variationId[]" id="variation_id" value='.$variantId.'>
                    </td>
                    <td class="align-middle" style="text-align: left;">
                        
                        <input type="number" class="form-control" name="tickets['.$variantId.']" id="tickets" value='.$outletItemData->tickets.'>
                    </td>
                    <td class="align-middle">
                        <input type="number" class="form-control" name="points['.$variantId.']" id="points" value='.$outletItemData->points.'>
                    </td>
                    <td class="align-middle">
                        <input type="number" class="form-control" name="kyat['.$variantId.']" id="kyat" value='.$outletItemData->kyat.'>
                    </td>
                    <td class="align-middle">
                        <input type="number" class="form-control purchasedPrice" name="purchased_price['.$variantId.']" value='.$outletItemData->purchased_price.'>
                    </td>
                    <td class="align-middle">
                        <input type="number" class="form-control purchaseQuantity" name="quantity['.$variantId.']" value="0">
                    </td>
                    <td class="purchaseTotal">'.$total.'</td>
                    <td class="align-middle"><a href="javascript:void(0)" onclick="deleteDisValue(this)" class="text-danger deleteBox">Delete</a></td>
                </tr>
        ';

        // $response = array();
        // $response['total'] = $total;
        // $response['html'] = $html;

        // return json_encode($response);
        return $html;
    }

    public function search_list_purchasedpricehistory(Request $request) {
    //    return $request;
        session()->start();
        session()->put(PURCHASEEDPRICEHISTORY_FROMDATE_FILTER, $request->fromDate);
        session()->put(PURCHASEEDPRICEHISTORY_TODATE_FILTER, $request->toDate);
        return redirect()->route('purchased-price-history.index');
    }

    public function purchasedpricehistory_search_reset() {
        session()->forget([
            PURCHASEEDPRICEHISTORY_FROMDATE_FILTER,
            PURCHASEEDPRICEHISTORY_TODATE_FILTER,
        ]);
        return redirect()->route('purchased-price-history.index');
    }

    public function search_list_distribute(Request $request) {
    //    return $request;
        session()->start();
        session()->put(DISTRIBUTE_FROMDATE_FILTER, $request->fromDate);
        session()->put(DISTRIBUTE_TODATE_FILTER, $request->toDate);
        session()->put(DISTRIBUT_FROMOUTLET_FILTER, $request->fromOutlet);
        session()->put(DISTRIBUT_TOOUTLET_FILTER, $request->toOutlet);
        session()->put(DISTRIBUT_VOUNCHERNO_FILTER, $request->vouncher_no);
        session()->put(DISTRIBUT_STATUS_FILTER, $request->status);
        return redirect()->route('distribute.index');
    }

    public function distribute_search_reset() {
        session()->forget([
            DISTRIBUTE_FROMDATE_FILTER,
            DISTRIBUTE_TODATE_FILTER,
            DISTRIBUT_FROMOUTLET_FILTER,
            DISTRIBUT_TOOUTLET_FILTER,
            DISTRIBUT_VOUNCHERNO_FILTER,
            DISTRIBUT_STATUS_FILTER,
        ]);
        return redirect()->route('distribute.index');
    }


    function priceChangeHistorySearch(Request $request){
        session()->start();
        session()->put(PCH_ITEM_CODE_FILTER, $request->item_code);
        session()->put(PCH_RECEIVED_DATE_FILTER, $request->received_date);
        return redirect()->route('report.price-changed-history');
    }

    function priceChangeHistoryReset(){
        session()->forget([
            PCH_ITEM_CODE_FILTER,
            PCH_RECEIVED_DATE_FILTER
        ]);
        return redirect()->route('report.price-changed-history');
    }

    function mainInvOutletOverviewSearch(Request $request){
        session()->start();
        session()->put(MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER, $request->date);

        return redirect()->route('main-outletleveloverview.index');
    }

    function mainInvOutletOverviewReset(){
        session()->forget([
            MAIN_OUTLET_LEVEL_OVERVIEW_DATE_FILTER,
        ]);
        return redirect()->route('main-outletleveloverview.index');
    }

    
    
}
