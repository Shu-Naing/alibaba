<?php
use Illuminate\Support\Facades\Route;  
use App\Http\Controllers\PosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DistributeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SellingPriceGroupController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SelectBoxController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\DistributeProductController;
use App\Http\Controllers\OutletStockOverviewController;
use App\Http\Controllers\OutletDistributeController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\OutletStockHistoryController;

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
    return view('auth.login');
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
    Route::resource('outlet-stock-overview', OutletStockOverviewController::class);
    Route::resource('outletstockhistory', OutletStockHistoryController::class);
    // Route::resource('issue-products', IssueProductController::class);

    Route::get('/issue/{id}/create', [IssueController::class, 'create'])->name('issue.create');
    Route::post('/issue', [IssueController::class, 'store'])->name('issue.store');
    Route::get('/issue/{id}/{from_outlet}/edit', [IssueController::class, 'edit'])->name('issue.edit');
    Route::patch('/issue/{id}', [IssueController::class, 'update'])->name('issue.update');

    Route::get('/outletdistribute', [OutletDistributeController::class, 'index'])->name('outletdistribute.index');
    Route::get('/outletdistribute/{id}/create', [OutletDistributeController::class, 'create'])->name('outletdistribute.create');
    Route::post('/outletdistribute', [OutletDistributeController::class, 'store'])->name('outletdistribute.store');
    Route::get('/outletdistribute/{id}/{from_outlet}/edit', [OutletDistributeController::class, 'edit'])->name('outletdistribute.edit');
    Route::patch('/outletdistribute/{id}', [OutletDistributeController::class, 'update'])->name('outletdistribute.update');

    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search-outlet-distributes', [SearchController::class, 'search_outlet_distributes'])->name('search-outlet-distributes');
    Route::get('/search-outlet-issue', [SearchController::class, 'search_outlet_issue'])->name('search-outlet-issue');
    // Route::resource('distribute/{id}', DistributeController::class);
    // Route::get('product', [ProductController::class, 'index'])->name('product');


    //proudcts excel-export
    Route::get('products-list',[ProductsController::class, 'listProduct'])->name('products.list');
    
    Route::get('products-sample-export',[ProductsController::class, 'exportSampleProduct'])->name('product.sample-export');
    Route::get('products-import',[ProductsController::class, 'importProduct'])->name('product.import');

    //Pos Route Start
    Route::get('pos',[PosController::class,'index'])->name('pos.index');
    Route::post('pos/add',[PosController::class,'addPos'])->name('pos.add');
    Route::get('pos/product/search',[PosController::class,'searchPosProduct'])->name('pos.product.search');
    Route::post('pos-item/add',[PosController::class,'addItemPos'])->name('positem.add');
    Route::post('pos-item/update',[PosController::class,'updateItemPos'])->name('positem.update');
    Route::delete('pos-item/remove',[PosController::class,'removeItemPos'])->name('positem.remove');

    Route::get('/select-box-data', [SelectBoxController::class, 'getData']);
    Route::get('/edit', [SelectBoxController::class, 'edit']);

    Route::get('/get-product-lists',[ProductsController::class,'get_product_lists']);
    Route::get('/get-outletdistir-product-lists',[ProductsController::class,'get_outletdistir_product_lists']);

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



    Route::get('/update-product-qty/{id}', [ProductsController::class, 'update_product_qty']);
    Route::get('/update-outdis-product-qty/{id}', [ProductsController::class, 'update_outdis_product_qty']);
    Route::get('/delete-dis-product/{id}', [ProductsController::class,'delete_dis_product']);
    Route::get('/delete-outdis-product/{id}', [ProductsController::class,'delete_outletdistirbute_product']);

    // Route::get('test', [TestController::class,'index'])->name('test.search');
    Route::get('report/products',[ReportController::class,'productReport'])->name('report.products');
    Route::get('products-export',[ReportController::class, 'exportProduct'])->name('product.export');
});

