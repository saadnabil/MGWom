<?php
use App\Http\Controllers\Api\Dashboard\DataController;
use App\Http\Controllers\Api\VendorDashboard\OrdersController;
use App\Http\Controllers\Api\VendorDashboard\AuthController;
use App\Http\Controllers\Api\VendorDashboard\ProfileController;
use App\Http\Controllers\Api\VendorDashboard\ProductsController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => 'v1/vendordashboard'], function(){
    Route::post('login' ,[AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:vendor'],function(){
        /**auth logout */
        Route::post('logout' ,[AuthController::class, 'logout']);
        Route::get('profile', [ProfileController::class, 'profile']);
         /**orders */
        Route::post('orders/delete' , [OrdersController::class, 'delete']);
        Route::get('orders/fulldata/export' , [OrdersController::class, 'fulldataexport']);
        Route::get('orders/changeStatus/{order}/{status}' , [OrdersController::class, 'changeStatus']);
        Route::resource('orders' , OrdersController::class)->only('index','show');
        /**orders */

        /**Products */
        Route::resource('products' , ProductsController::class)->only('index','store','update','destroy');
         /**Products */

    });

    Route::get('data/policy', [DataController::class, 'policy']);
    Route::get('data/security', [DataController::class, 'security']);
});
