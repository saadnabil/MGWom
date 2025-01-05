<?php

use App\Http\Resources\Api\CartResource;
use App\Jobs\SendEmailOrderDetails;
use App\Jobs\SendOrderDetailsOnMail;
use App\Jobs\SendOtpOnMail;
use App\Mail\SendOrderDetails;
use App\Models\Country;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;

function generateGuestToken(){
    // Check if the user exists, otherwise create a guest user
    $uniqueid = uniqid();
    $user = User::firstOrCreate(
        ['email' => 'Guest'. $uniqueid.'womniz.com'],
        ['name' => 'Guest' .$uniqueid],
        ['country_id' => 1], //default egypt
    );

    $customClaims = [
        'is_guest' => true,  // Identify the token as a guest token
    ];

    // Generate a JWT token for the guest user
    $token = JWTAuth::claims($customClaims)->fromUser($user);
    $user['token'] = $token;
    return $user;
}



function getScratchGameDiscount($country){
    return isset(json_decode(setting('scratch_discount'),true)[$country->country]) ? json_decode(setting('scratch_discount'),true)[$country->country] : 10;
}

function resource_collection($resource): array
{
    return json_decode($resource->response()->getContent(), true) ?? [];
}

function getShipInformation(){
    $lang = app()->getLocale();
    $ship_information = json_decode(setting('ship_information'),true);
    return $ship_information[$lang];
}

function checkOrderStatus($status){
    if(in_array($status,['pending','delivered','returned','canceled','delivery_failed','shipped','ready_to_ship'])){
        return true;
    }
    return false;
}


function sendOtpWithVonage($number = '201098277871')
{
    $basic  = new \Vonage\Client\Credentials\Basic("72b4f1f4", "wL7XlR4bFChlCgCx");
    $client = new \Vonage\Client(new \Vonage\Client\Credentials\Container($basic));
    $request = new \Vonage\Verify\Request($number, "Vonage");
    $response = $client->verify()->start($request);
    return $response->getRequestId();
}

function verifyOtpWithVonage($request_id, $code){
    $basic  = new \Vonage\Client\Credentials\Basic("72b4f1f4", "wL7XlR4bFChlCgCx");
    $client = new \Vonage\Client(new \Vonage\Client\Credentials\Container($basic));
    try{
        $result = $client->verify()->check($request_id, $code);
        return $result->getStatus() === 0 ? true : false;
    }
    catch (\Exception $e) {
        // Handle the exception or log the error
        return false;
    }
}



function getReturnOrderInformation(){
    $lang = app()->getLocale();
    $return_order = json_decode(setting('return_order'),true);
    return $return_order[$lang];
}

function langs(){
    return ['en', 'ar'];
}

function spin_game_array(){
    return [
        '1' => 'digit_one',
        '2' => 'digit_two',
        '3' => 'digit_three',
        '4' => 'digit_four',
        '5' => 'digit_five',
        '6' => 'digit_six',
        '7' => 'digit_seven',
        '8' => 'digit_eight',
        '9' => 'digit_nine',
    ];
}

function generate_otp_function(){
    return str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

function checkValidAppliedCouponBeforeSubmitOrder($coupon , $timezone){
    if($coupon == null){
        return true;
    }
    if ($coupon->expiration_date >= Carbon::now($timezone)->format('Y-m-d') && $coupon->status == 'pending') {
        return true;
    } else {
       return false;
    }
}

function generateTimeSlotsBetweenIntervals($start_time, $end_time, $interval)
{
    $start = Carbon::parse($start_time);
    $end = Carbon::parse($end_time);

    $slots = [];

    while ($start < $end) {
        $slots[] = $start->format('h:i A');
        $start->addHours($interval);
    }
    return $slots;
}

// function generateTimeSlotsBetweenIntervals($start_time, $end_time, $interval = '1 hour'){
//     $start = Carbon::parse($start_time);
//     $end = Carbon::parse($end_time);

//     // Fix: Ensure the interval format is correct (e.g., 'PT1H' for 1 hour)
//     $interval = new DateInterval('PT' . strtoupper($interval[0]) . 'H');
//     $time_slots = [];

//     // Create a DatePeriod to iterate over the time range
//     $period = new DatePeriod($start, $interval, $end);
//     foreach ($period as $slot) {
//         $time_slots[] = ['time' => $slot->format('h:i A') , 'booked' => 0 ];
//     }
//     // Add the end time to ensure it's included in the slots
//     return $time_slots;
// }



function get_day_calendar_index(){
    return [
        'Saturday' => 1,
        'Sunday' => 2,
        'Monday' => 3 ,
        'Tuesday' => 4,
        'Wednesday' => 5,
        'Thursday' => 6,
        'Friday' => 7
    ];
}

/**vonage */
// function create_new_otp($email , $request_id){
//     Otp::where(['email' => $email])->delete();
//     Otp::create([
//         'email' => $email,
//         'request_id' => $request_id,
//     ]);
//     return;
// }
/**vonage */

function create_new_otp($email , $code){
    Otp::where(['email' => $email])->delete();
    Otp::create([
        'email' => $email,
        'code' => $code,
    ]);

     /**Dispatch job for sending email */
     $data = [
        'email' => $email,
        'code' => $code,
     ];
     $data = [
        'email' => $email,
        'code' => $code,
    ];
     dispatch(new SendOtpOnMail($data));
     /**Dispatch job for sending email */
    return;
}


function send_order_details_email($order){
    $order->load('user.addresses','address','orderDetails.product.brand','orderDetails.product.vendor.categories','orderDetails.product.categories','orderDetails.product_variant');
    dispatch(new SendEmailOrderDetails($order));
}


function weekDays (){
    return ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
}

// function generate_code_unique() {
//     // Generate a random prefix of length 2 using letters
//     $prefix = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

//     // Get the current date in the format "YmdHis" (YearMonthDayHourMinuteSecond)
//     $currentDate = date('YmdHis');

//     // Generate a random number between 1000 and 9999
//     $randomNumber = mt_rand(1000, 9999);

//     // Combine the prefix, date, and random number to create the code
//     $shipmentCode = $prefix . $currentDate . $randomNumber;

//     return $shipmentCode;
// }



// function generateQrCode($shipmentCode){
//     return QrCode::size(300)->generate($shipmentCode);
// }


function sendFCM($device_token,$title,$body) {
    $firebase = (new Factory)
        ->withServiceAccount(config('services.firebase.credentials.file'));
    // Get the FCM service
    $messaging = $firebase->createMessaging();
    // Send a message
   $response =  $messaging->send([
        'token' => $device_token,
        'notification' => [
            'title' => $title,
            'body' => $body,
        ],
        'data' => [

        ],
    ]);
    return response()->json(['message' =>'FCM message sent successfully!', 'data' => $response],200 ) ;
}
