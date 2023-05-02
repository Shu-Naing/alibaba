<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\DeliInfoController;
use App\Http\Controllers\Api\StroeController;
use App\Http\Controllers\Api\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class,'register']);
Route::post('login', [RegisterController::class,'login']);

Route::middleware(['auth:sanctum'])->group( function () {
    //write api routes here
    Route::post('deactivate', [RegisterController::class,'deactivate']);
    Route::get('dashboard', [DashboardController::class,'index']);
    Route::get('product-list',[ProductController::class,'index']);
    Route::post('product-add',[ProductController::class,'add']);
    Route::post('product-detail/{id}',[ProductController::class,'detail']);
    Route::get('order-list',[OrderController::class,'index']);
    Route::get('order-detail/{id}',[OrderController::class,'detail']);
    Route::post('order-cancel/{id}',[OrderController::class,'cancel']);
    Route::get('customer-list',[CustomerController::class,'index']);
    Route::get('customer-detail/{id}',[CustomerController::class,'detail']);
    Route::get('customer-block/{id}',[CustomerController::class,'block']);
    Route::get('deli-info-list',[DeliInfoController::class,'index']);
    Route::post('deli-info-add',[DeliInfoController::class,'add']);
    Route::post('deli-info-edit',[DeliInfoController::class,'edit']);
    Route::get('admin-profile',[RegisterController::class,'profile']);
    Route::get('store-setting',[StroeController::class,'store_setting']);
    Route::post('billing-info-delete',[StroeController::class,'delete_billing_info']);
    Route::post('billing-info-add',[StroeController::class,'add']);
    Route::post('billing-info-edit',[StroeController::class,'edit']);
});

Route::get('deli-info-list',[DeliInfoController::class,'index']);
Route::post('deli-info-add',[DeliInfoController::class,'add']);
Route::put('deli-info-edit/{id}',[DeliInfoController::class,'edit']);

Route::post('billing-info-add',[StroeController::class,'add']);
Route::put('billing-info-edit/{id}',[StroeController::class,'edit']);
Route::delete('billing-info-delete/{id}',[StroeController::class,'delete']);

Route::post('store-setting-add',[StroeController::class,'storeSettingAdd']);
Route::put('store-setting-edit/{id}',[StroeController::class,'storeSettingEdit']);
Route::get('store-setting-index',[StroeController::class,'storeSettingIndex']);

Route::post('admin-profile',[RegisterController::class,'profile']);




Route::fallback(function(){
    return response()->json([
        'status' => '404',
        'message' => 'Page Not Found. Check url or method'], 404);
});