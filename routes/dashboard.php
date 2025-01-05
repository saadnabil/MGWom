<?php
use App\Http\Controllers\Api\Dashboard\ActivitiesController;
use App\Http\Controllers\Api\Dashboard\AdminsController;
use App\Http\Controllers\Api\Dashboard\BrandsController;
use App\Http\Controllers\Api\Dashboard\CategoriesController;
use App\Http\Controllers\Api\Dashboard\ColorsController;
use App\Http\Controllers\Api\Dashboard\DataController;
use App\Http\Controllers\Api\Dashboard\ImagesController;
use App\Http\Controllers\Api\Dashboard\InvoicesController;
use App\Http\Controllers\Api\Dashboard\OrdersController;
use App\Http\Controllers\Api\Dashboard\PermissionsController;
use App\Http\Controllers\Api\Dashboard\ProductImagesController;
use App\Http\Controllers\Api\Dashboard\ProductsCategoriesController;
use App\Http\Controllers\Api\Dashboard\ProductsController;
use App\Http\Controllers\Api\Dashboard\ProductSpecificationsController;
use App\Http\Controllers\Api\Dashboard\ProductVariantSkusController;
use App\Http\Controllers\Api\Dashboard\RestoreRequestController;
use App\Http\Controllers\Api\Dashboard\RolesController;
use App\Http\Controllers\Api\Dashboard\ScratchGameController;
use App\Http\Controllers\Api\Dashboard\ShipmentController;
use App\Http\Controllers\Api\Dashboard\SizesController;
use App\Http\Controllers\Api\Dashboard\SpinGameController;
use App\Http\Controllers\Api\Dashboard\UsersController;
use App\Http\Controllers\Api\Dashboard\VendorsController;
use App\Http\Controllers\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/dashboard'], function(){
    Route::post('products/bulk/upload' ,[ProductsController::class, 'bulkupload' ]);

    Route::post('login' ,[AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:admin'],function(){

        Route::get('data', [DataController::class, 'index']);
        Route::get('data/cities', [DataController::class, 'cities']);
        Route::get('data/productSetting', [DataController::class, 'productSetting']);

        Route::get('products/fulldata/export', [ProductsController::class, 'fulldataexport']);

        // Route::post('products/bulk/upload' ,[ProductsController::class, 'bulkupload' ]);
        Route::post('products/delete', [ProductsController::class, 'delete']);
        Route::post('products/updatethumbnail/{product}', [ProductsController::class, 'updateThumbnail']);
        Route::resource('products' , ProductsController::class)->only('index','store','update','show');

        /**product images */
        Route::resource('product-images' , ProductImagesController::class)->only('destroy','update');
        Route::post('product-images/upload/{product}' , [ProductImagesController::class, 'uploadimages']);

        /**pulk images */
        Route::post('images/upload' , [ImagesController::class, 'upload']);
        Route::get('images/list' , [ImagesController::class, 'list']);


        /**product variants */
        Route::resource('product-variant-skus', ProductVariantSkusController::class)->only('store', 'update', 'destroy');

         /**product categories */
         Route::post('product-categories/update/{product}', [ProductsCategoriesController::class, 'updateCategories']);
         Route::get('product-categories/delete/{product}/{category}', [ProductsCategoriesController::class, 'deleteCategory']);


        /**product specifications */
        Route::resource('product-specifications', ProductSpecificationsController::class)->only('store', 'update', 'destroy');

         /**product specifications */
         Route::resource('product-specifications', ProductSpecificationsController::class)->only('store', 'update', 'destroy');

        /**colors */
        Route::resource('colors' , ColorsController::class)->only('index');

        /**orders */
        Route::post('orders/delete' , [OrdersController::class, 'delete']);
        Route::get('orders/fulldata/export' , [OrdersController::class, 'fulldataexport']);
        Route::get('orders/changeStatus/{order}/{status}' , [OrdersController::class, 'changeStatus']);
        Route::resource('orders' , OrdersController::class)->only('index','show');

        /**categories */
        Route::get('categories/lastParentChildCategories/{parentCategory}' , [CategoriesController::class, 'getLastChildCategoriesForParentCategory']);
        Route::get('categories/main' , [CategoriesController::class, 'mainCategories']);
        Route::get('categories/sub/{category}' , [CategoriesController::class, 'subCategories']);

        Route::resource('categories' , CategoriesController::class)->only('index','store','update','destroy');

        /**brands */
        Route::get('brands/sub/{brand}' , [BrandsController::class, 'subCategories']);
        Route::resource('brands' , BrandsController::class)->only('index','store','update','destroy');

        /**activities */
        Route::resource('activities', ActivitiesController::class)->only('index');

        /**admins */
        Route::get('admins/fulldata/export', [AdminsController::class, 'fulldataexport']);
        Route::post('admins/delete', [AdminsController::class, 'delete']);
        Route::post('admins/{admin}/switchstatus', [AdminsController::class, 'switchstatus']);
        Route::resource('admins', AdminsController::class)->only('index','store','update','show');

        /**users */
        Route::post('users/{user}/switchstatus', [UsersController::class, 'switchstatus']);
        Route::get('users/fulldata/export', [UsersController::class, 'fulldataexport']);
        Route::resource('users', UsersController::class)->only('index','store','update','show');
        Route::post('users/delete', [UsersController::class, 'delete']);

        /**sizes */
        Route::get('sizes', [SizesController::class , 'index']);

        /**vendors */
        Route::get('vendors/fulldata/export', [VendorsController::class, 'fulldataexport']);
        Route::post('vendors/brands/add', [VendorsController::class, 'addbrand']);
        Route::post('vendors/delete', [VendorsController::class, 'delete']);
        Route::post('vendors/{vendor}/switchstatus', [VendorsController::class, 'switchstatus']);
        Route::resource('vendors',VendorsController::class)->only('index','store','update','show');

        /**scratch game */
        Route::get('scratch/information',[ScratchGameController::class , 'scratchInformation']);
        Route::post('scratch/information/update',[ScratchGameController::class , 'updateDiscountValue']);

         /**spin game */
        Route::get('spin/information',[SpinGameController::class , 'spinInformation']);
        Route::post('spin/information/update',[SpinGameController::class , 'spinInformationUpdate']);

        /**restore request */
        Route::get('restoreAccountRequest' ,[RestoreRequestController::class, 'index']);
        Route::post('restoreAccountRequest/changeStatus/{restoreAccountRequest}' ,[RestoreRequestController::class, 'changeStatus']);

        /**auth logout */
        Route::post('logout' ,[AuthController::class, 'logout']);

        /**Invoice PDF */
        Route::get('invoice', [InvoicesController::class, 'sendInvoice']);

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class)->only('index');

        Route::group(['prefix' => 'shipment'],function(){
            Route::post('createShipment', [ShipmentController::class, 'createShipment']);
            Route::post('trackShipment', [ShipmentController::class, 'trackShipment']);
        });

    });
    Route::get('data/policy', [DataController::class, 'policy']);
    Route::get('data/security', [DataController::class, 'security']);
});
