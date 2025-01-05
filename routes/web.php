<?php

use App\Http\Controllers\Api\Dashboard\ColorsController;
use App\Mail\OtpMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
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
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('test', function(){

});

Route::get('test-mail-otp', function(){
    $data = [
        'email' => 'saadnabil00@gmail.com',
        'code' => '1234',
    ];
    Mail::to('saadnabil00@gmail')->send(new OtpMail($data));
});

Route::get('test-mail-order',function(){
    $order = Order::first();
    send_order_details_email($order);
    dd('success');
});

Route::get('/color/{name}/hex', [ColorsController::class, 'getColorHex']);



