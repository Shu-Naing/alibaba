<?php
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SelectBoxController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DistributeController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\SizeVariantController;
use App\Http\Controllers\PosItemAlertController;
use App\Http\Controllers\OutletDistributeController;
use App\Http\Controllers\DistributeProductController;
use App\Http\Controllers\SellingPriceGroupController;
use App\Http\Controllers\OutletlevelhistoryController;
use App\Http\Controllers\OutletStockHistoryController;
use App\Http\Controllers\OutletLevelOverviewController;
use App\Http\Controllers\OutletStockOverviewController;
use App\Http\Controllers\PurchasedPriceHistoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
  
Route::get('/', function () {
    if(Auth::user() != null) {
        return redirect()->route('home');
    }else {
        return view('auth.login');
    }
});
  
Auth::routes();
  
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth','permission']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    
    Route::resource('categories', CategoriesController::class);
    Route::resource('brands', BrandsController::class);
    Route::resource('units', UnitsController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('sellingprice', SellingPriceGroupController::class);
    Route::resource('outlets', OutletController::class);
    Route::resource('machine', MachineController::class);
    Route::resource('distribute-products', DistributeProductController::class);
    Route::resource('distribute', DistributeController::class);
    Route::get('listdistributedetail', [DistributeController::class, 'listdistributedetail'])->name('listdistributedetail');
    Route::get('distribute-detail-export',[DistributeController::class, 'distributeDetailExport'])->name('distribute-detail-export');
    Route::get('bodanddepartment-export',[DistributeController::class, 'bodanddepartmentExport'])->name('bodanddepartment-export');
    Route::get('/distribute-preview/{distribute_id}',[DistributeController::class, 'preview'])->name('distribute.preview');
    Route::resource('outlet-stock-overview', OutletStockOverviewController::class);
    Route::resource('outletstockhistory', OutletStockHistoryController::class);
    Route::resource('outletleveloverview', OutletLevelOverviewController::class);
    Route::resource('size-variant', SizeVariantController::class);
    Route::resource('stockalert', PosItemAlertController::class);
    Route::resource('adjustment', AdjustmentController::class);
    Route::resource('damage', DamageController::class);
    Route::resource('purchase', PurchaseController::class);
    Route::resource('sell', SellController::class);
    Route::get('purchase-detail-export/{grn_no}',[PurchaseController::class, 'purchaseDetailExport'])->name('purchase-detail-export');
    // Route::resource('issue-products', IssueProductController::class);

    Route::get('distribute/{id}/{from_outlet}/edit', [DistributeController::class, 'edit'])->name('distribute.edit');

    Route::get('/issue', [IssueController::class, 'index'])->name('issue.index');
    Route::get('/issue/{id}/create', [IssueController::class, 'create'])->name('issue.create');
    Route::post('/issue', [IssueController::class, 'store'])->name('issue.store');
    Route::get('/issue/{id}/{from_outlet}/{to_machine}/edit', [IssueController::class, 'edit'])->name('issue.edit');
    Route::patch('/issue/{id}', [IssueController::class, 'update'])->name('issue.update');
    Route::get('/issue/{id}', [IssueController::class, 'show'])->name('issue.show');

    Route::get('/outletdistribute', [OutletDistributeController::class, 'index'])->name('outletdistribute.index');
    Route::get('/outletdistribute/{id}/create', [OutletDistributeController::class, 'create'])->name('outletdistribute.create');
    Route::post('/outletdistribute', [OutletDistributeController::class, 'store'])->name('outletdistribute.store');
    Route::get('/outletdistribute/{id}/{from_outlet}/edit', [OutletDistributeController::class, 'edit'])->name('outletdistribute.edit');
    Route::patch('/outletdistribute/{id}', [OutletDistributeController::class, 'update'])->name('outletdistribute.update');

    Route::get('/outletstockoverview/{id}/create', [OutletStockOverviewController::class, 'create'])->name('outletstockoverview.create');
    Route::get('/outletstockoverview/{id}/edit', [OutletStockOverviewController::class, 'edit'])->name('outletstockoverview.edit');
    Route::post('/outletstockoverview', [OutletStockOverviewController::class, 'store'])->name('outletstockoverview.store');
    Route::patch('/outletstockoverview/{id}/update', [OutletStockOverviewController::class, 'update'])->name('outletstockoverview.update');
    // Route::get('/outlet-machine-item', [OutletStockOverviewController::class, 'getOutletMachineItem'])->name('outletmachineitem');

    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search-damage', [SearchController::class, 'search_damage'])->name('search-damage');
    Route::get('/search-outlet-distributes', [SearchController::class, 'search_outlet_distributes'])->name('search-outlet-distributes');
    Route::get('/search-outlet-issue', [SearchController::class, 'search_outlet_issue'])->name('search-outlet-issue');
    Route::post('/search-list-distribute-detail', [SearchController::class, 'search_list_distribute_detail'])->name('search-list-distribute-detail');
    Route::post('/search-bodanddepartment', [SearchController::class, 'search_bodanddepartment'])->name('search-bodanddepartment');
    Route::post('/search-list-damage', [SearchController::class, 'search_list_damage'])->name('search-list-damage');
    Route::post('/search-list-adjustment', [SearchController::class, 'search_list_adjustment'])->name('search-list-adjustment');
    Route::post('/search-list-purchasedpricehistory', [SearchController::class, 'search_list_purchasedpricehistory'])->name('search-list-purchasedpricehistory');
    Route::post('/search-list-distribute', [SearchController::class, 'search_list_distribute'])->name('search-list-distribute');
    Route::post('/search-purchase-detail', [SearchController::class, 'search_purchase_detail'])->name('search-purchase-detail');

    Route::get('/search-reset', [SearchController::class, 'search_reset'])->name('search-reset');
    Route::get('/bodanddepartment-reset', [SearchController::class, 'bodanddepartment_reset'])->name('bodanddepartment-reset');
    Route::get('/damage-search-reset', [SearchController::class, 'damage_search_reset'])->name('damage-search-reset');
    Route::get('/adjustment-search-reset', [SearchController::class, 'adjustment_search_reset'])->name('adjustment-search-reset');
    Route::get('/purchasedpricehistory-search-reset', [SearchController::class, 'purchasedpricehistory_search_reset'])->name('purchasedpricehistory-search-reset');
    Route::get('/purchase-search-reset', [SearchController::class, 'purchase_search_reset'])->name('purchase-search-reset');
    Route::get('/distribute-search-reset', [SearchController::class, 'distribute_search_reset'])->name('distribute-search-reset');
    Route::get('/search-purchase', [SearchController::class, 'search_purchase'])->name('search-purchase');
    
    // Route::resource('distribute/{id}', DistributeController::class);
    // Route::get('product', [ProductController::class, 'index'])->name('product');


    //proudcts excel-export
    Route::get('products-list',[ProductsController::class, 'listProduct'])->name('products.list');
    Route::post('products-add-stock/{variation_id}',[ProductsController::class, 'addStock'])->name('products.add-stock');
    
    Route::get('products-sample-export',[ProductsController::class, 'exportSampleProduct'])->name('product.sample-export');
    Route::post('products-import',[ProductsController::class, 'importProduct'])->name('product.import');
    Route::post('search-products',[SearchController::class, 'searchProduct'])->name('product.search');
    Route::get('products-reset',[SearchController::class, 'resetProduct'])->name('product.reset');

    //Pos Route Start
    Route::get('pos',[PosController::class,'index'])->name('pos.index');
    Route::post('pos/add',[PosController::class,'addPos'])->name('pos.add');
    Route::get('pos/product/search',[PosController::class,'searchPosProduct'])->name('pos.product.search');
    Route::post('pos-item/add',[PosController::class,'addItemPos'])->name('positem.add');
    Route::post('pos-item/update',[PosController::class,'updateItemPos'])->name('positem.update');
    Route::delete('pos-item/remove',[PosController::class,'removeItemPos'])->name('positem.remove');
    Route::post('pos-item/alert',[PosController::class,'alertItemPos'])->name('positem.alert');

    Route::get('/select-box-data', [SelectBoxController::class, 'getData']);
    Route::get('/edit', [SelectBoxController::class, 'edit']);

    Route::get('/get-product-lists',[ProductsController::class,'get_product_lists']);
    Route::get('/get-product-lists-puchase',[ProductsController::class,'get_product_lists_purchase']);
    Route::get('/get-outletdistir-product-lists',[ProductsController::class,'get_outletdistir_product_lists']);
    Route::get('/get-outletdistir-issue-lists',[ProductsController::class,'get_outletdistir_issue_lists']);
    Route::get('/get-damage-product-lists',[ProductsController::class,'get_damage_product_lists']);

    Route::get('/sellingprice/{id}/deactivate', [SellingPriceGroupController::class, 'deactivate'])
    ->name('sellingprice.deactivate');

    Route::get('/sellingprice/{id}/activate', [SellingPriceGroupController::class, 'activate'])
    ->name('sellingprice.activate');

    Route::put('sellingprice/{id}/toggle', [SellingPriceGroupController::class, 'toggle'])
    ->name('sellingprice.toggle');

    Route::post('/sellingprice/{id}/status', [SellingPriceGroupController::class, 'toggle'])->name('sellingprice.updateStatus');

    Route::put('/sellingprice/{id}/sell', [SellingPriceGroupController::class, 'sell'])->name('sellingprice.sell');


    Route::get('/courses/{id}/deactivate', [SellingPriceGroupController::class, 'deactivate'])->name('courses.deactivate');
    Route::get('/courses/{id}/activate', [SellingPriceGroupController::class, 'activate'])->name('courses.activate');

    Route::get('/update-product-qty/{distribute_product_id}/{variant_id}', [ProductsController::class, 'update_product_qty']);
    Route::get('/update-outdis-product-qty/{outlet_distribute_id}/{variant_id}', [ProductsController::class, 'update_outdis_product_qty']);
    Route::get('/delete-dis-product/{id}', [ProductsController::class,'delete_dis_product']);
    Route::get('/delete-outdis-product/{id}', [ProductsController::class,'delete_outletdistirbute_product']);
    // Route::get('test', [TestController::class,'index'])->name('test.search');

    Route::get('report/products',[ReportController::class,'productReport'])->name('report.products');
    Route::get('report/machine',[ReportController::class,'machineReport'])->name('report.machine');
    Route::get('report/outletstockoverview',[ReportController::class,'outletstockoverviewReport'])->name('report.outletstockoverview');
    Route::get('report/bodanddepartment',[ReportController::class,'bodanddepartmentReport'])->name('report.bodanddepartment');
    Route::post('outletstockoverview-search',[ReportController::class,'search'])->name('outletstockoverview.search');
    Route::get('outletstockoverview-reset',[ReportController::class,'reset'])->name('outletstockoverview.reset');
    // Route::get('report/outletdistributeproduct',[OutletDistributeController::class,'index'])->name('outletdistribute.index');
    Route::get('report/outletdistributeproduct/{id}',[OutletDistributeController::class,'show'])->name('outletdistribute.show');

    Route::get('products-export',[ReportController::class, 'exportProduct'])->name('product.export');
    Route::get('damage-export',[DamageController::class, 'exportDamage'])->name('damage.export');
    Route::get('adjustment-export',[AdjustmentController::class, 'exportAdjustment'])->name('adjustment.export');
    Route::get('outletstockoverview-export',[ReportController::class, 'exportOutletstockoverview'])->name('outletstockoverview.export');
    Route::get('outletstockoverview-sample-export',[OutletStockOverviewController::class, 'exportSampleOutletstockoverview'])->name('outletstockoverview.sample-export');
    Route::post('outletstockoverview-import',[OutletStockOverviewController::class, 'importOutletstockoverview'])->name('outletstockoverview.import');
    Route::get('outletstockhistory-export',[OutletStockHistoryController::class, 'exportOutletstockhistory'])->name('outletstockhistory.export');
    Route::post('outletstockhistory-search',[OutletStockHistoryController::class, 'search'])->name('outletstockhistory.search');
    Route::get('outletstockhistory-reset',[OutletStockHistoryController::class, 'reset'])->name('outletstockhistory.reset');

    Route::get('checkoutletstockhistory',[OutletStockHistoryController::class, 'checkoutletstockhistory'])->name('checkoutletstockhistory');
    Route::get('checkoutletstockoverview',[OutletStockOverviewController::class, 'checkoutletstockoverview'])->name('checkoutletstockoverview');
    Route::get('checkoutletlevelhistory',[OutletlevelhistoryController::class, 'checkoutletlevelhistory'])->name('checkoutletlevelhistory');
    Route::get('checkoutletleveloverview',[OutletLevelOverviewController::class, 'checkoutletleveloverview'])->name('checkoutletleveloverview');
    Route::get('updatephysicalqty',[OutletStockOverviewController::class, 'updatephysicalqty'])->name('updatephysicalqty');


    Route::get('purchased-price-history',[PurchasedPriceHistoryController::class,'index'])->name('purchased-price-history.index');
    Route::get('purchased-price-history-export',[PurchasedPriceHistoryController::class,'export'])->name('purchased-price-history.export');

    Route::get('outlethistory',[OutletController::class,'history'])->name('outlethistory.history');

    Route::get('outletlevelhistory',[OutletlevelhistoryController::class,'index'])->name('outletlevelhistory.index');
    Route::get('outletlevelhistory-export',[OutletlevelhistoryController::class,'export'])->name('outletlevelhistory.export');
    Route::post('outletlevelhistory-search',[SearchController::class,'outletlevelhistorySearch'])->name('outletlevelhistory.search');
    Route::get('outletlevelhistory-reset',[SearchController::class,'resetOutletlevelhistory'])->name('outletlevelhistory.reset');
    
    // Route::get('outletleveloverview',[OutletLevelOverviewController::class,'index'])->name('outletleveloverview.index');
    Route::get('updateoutletlevelphysicalqty',[OutletLevelOverviewController::class,'updateoutletlevelphysicalqty'])->name('updateoutletlevelphysicalqty');
    Route::get('outletlevelopeningqty-sample-export',[OutletLevelOverviewController::class, 'exportSampleOutletlevelopeningqty'])->name('outletlevelopeningqty.sample-export');
    Route::post('outletlevelopeningqty-import',[OutletLevelOverviewController::class, 'importOutletlevelopeningqty'])->name('outletlevelopeningqty.import');

    Route::get('purchaseAdd-sample-export',[PurchaseController::class, 'exportSamplePurchaseAdd'])->name('purchaseAdd.sample-export');
    Route::post('purchaseAdd-import',[PurchaseController::class, 'importPurchaseAdd'])->name('purchaseAdd.import');
  
    Route::get('getoutletItem',[OutletStockOverviewController::class,'getoutletItem'])->name('getoutletItem');
    Route::get('outletleveloverview-export',[OutletLevelOverviewController::class,'export'])->name('outletleveloverview.export');
    Route::post('outletleveloverview-search',[SearchController::class,'outletlevelverviewSearch'])->name('outletleveloverview.search');
    Route::get('outletleveloverview-reset',[SearchController::class,'resetOutletleveloverview'])->name('outletleveloverview.reset');
    
    Route::get('get-machine',[MachineController::class,'getMachineByOutletId'])->name('get-machine');

    Route::get('updatedistributeproductdetailqty',[DistributeController::class,'updatedistributeproductdetailqty'])->name('updatedistributeproductdetailqty');

    Route::get('purchasedetailcountry',[PurchaseController::class,'purchasedetailcountry'])->name('purchasedetailcountry');

    Route::get('generatedamagecode',[DamageController::class,'demageGenerateCode'])->name('generatedamagecode');

    // Route::get('test',[TestController::class,'test'])->name('test');
    // Route::post('testform',[TestController::class,'testform'])->name('testform');

    //New Set Up section

    Route::resource('countries', CountryController::class);
    Route::resource('companies', CompanyController::class);
});

