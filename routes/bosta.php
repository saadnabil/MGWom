<?php

use App\Http\Controllers\Api\Integrations\BostaController;
use Illuminate\Support\Facades\Route;

Route::post('deliveries/addDelivery', [BostaController::class, 'addDelivery']);
Route::post('deliveries/addDelivery/bulk', [BostaController::class, 'addDeliveryBulk']);
Route::delete('deliveries/{trackingNumber}/business/terminate', [BostaController::class, 'terminate']);
Route::post('deliveries/search',  [BostaController::class, 'search']);
Route::get('deliveries/business/{trackingNumber}',  [BostaController::class, 'show']);
Route::put('deliveries/update/{trackingNumber}',  [BostaController::class, 'update']);
