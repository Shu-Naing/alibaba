<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\SelectBoxController;
use App\Http\Controllers\DistributeProductController;

  
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

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    
    Route::resource('categories', CategoriesController::class);
    Route::resource('outlets', OutletController::class);
    Route::resource('machine', MachineController::class);
    Route::resource('distribute-products', DistributeProductController::class);

    Route::get('/select-box-data', [SelectBoxController::class, 'getData']);
    Route::get('/edit', [SelectBoxController::class, 'edit']);


});
