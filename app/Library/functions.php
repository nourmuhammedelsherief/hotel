<?php

use App\Models\RestaurantSensitivity;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use App\Models\Setting;
//use FCM;

function domain()
{
    return 'easyhotelll.com';
}

function validateRules($errors, $rules)
{

    $error_arr = [];

    foreach ($rules as $key => $value) {

        if ($errors->get($key)) {

            array_push($error_arr, array('key' => $key, 'value' => $errors->first($key)));
        }
    }

    return $error_arr;
}

function randNumber($length)
{

    $seed = str_split('0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $rand;
}

function generateApiToken($userId, $length)
{

    $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789');

    shuffle($seed);

    $rand = '';

    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];

    return $userId * $userId . $rand;
}

function UploadImage($inputRequest, $prefix, $folderNam)
{
    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize(600, 600, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $image);
    return $image ? $image : false;
}

function UploadVideo($file , $folderName)
{
    if ($file) {
        $filename = time() . '' . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
        $path = public_path() . $folderName;
        $file->move($path, $filename);
        return $filename;
    }
}

function UploadVideoEdit($file,$folderName ,$old)
{
    if ($old) {
        @unlink(public_path($folderName . $old));
    }
    if ($file) {
        $filename = time() . '' . rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
        $path = public_path() . $folderName;
        $file->move($path, $filename);
        return $filename;
    }
}

function UploadImageEdit($inputRequest, $prefix, $folderNam, $oldImage)
{
    @unlink(public_path('/' . $folderNam . '/' . $oldImage));
    $image = time() . '' . rand(11111, 99999) . '.' . $inputRequest->getClientOriginalExtension();
    $destinationPath = public_path('/' . $folderNam);
    $img = Image::make($inputRequest->getRealPath());
    $img->resize(500, 500, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath . '/' . $image);
    return $image ? $image : false;
}

####### Check Payment Status ######
function MyFatoorahStatus($api, $PaymentId)
{
    // dd($PaymentId);
    $token = $api;
    $basURL = "https://api-sa.myfatoorah.com/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/GetPaymentStatus",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"Key\": \"$PaymentId\",\"KeyType\": \"PaymentId\"}",
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

// ===============================  MyFatoorah public  function  =========================
function MyFatoorah($api, $userData)
{
    // dd($userData);
    $token = $api;
    $basURL = "https://api-sa.myfatoorah.com/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$basURL/v2/ExecutePayment",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $userData,
        CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

/**
 * calculate the distance between tow places on the earth
 *
 * @param latitude $latitudeFrom
 * @param longitude $longitudeFrom
 * @param latitude $latitudeTo
 * @param longitude $longitudeTo
 * @return double distance in KM
 */
function distanceBetweenTowPlaces($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
{
    $long1 = deg2rad($longitudeFrom);
    $long2 = deg2rad($longitudeTo);
    $lat1 = deg2rad($latitudeFrom);
    $lat2 = deg2rad($latitudeTo);
    //Haversine Formula
    $dlong = $long2 - $long1;
    $dlati = $lat2 - $lat1;
    $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);
    $res = 2 * asin(sqrt($val));
    $radius = 6367.756;
    return ($res * $radius);
}


/**
 *  Taqnyat sms to send message
 */
function taqnyatSms($msgBody, $reciver)
{
    $setting = Setting::find(1);
    $bearer = $setting->bearer_token;
    $sender = $setting->sender_name;
    $taqnyt = new TaqnyatSms($bearer);

    $body = $msgBody;
    $recipients = $reciver;
    $message = $taqnyt->sendMsg($body, $recipients, $sender);
    return $message;
}
function express_payment($merchant_key, $password, $amount , $success_url, $orderId, $user_name, $email)
{
    $order_id = 'order-' . mt_rand(1000, 9999);
    $currency = 'SAR';
    $order_description = 'pay order value';
    $str_to_hash = $orderId . $amount . $currency . $order_description . $password;
    $hash = sha1(md5(strtoupper($str_to_hash)));
    dd($success_url . ''. $orderId);
    $main_req = array(
        'action' => 'SALE',
        'edfa_merchant_id' => $merchant_key,
        'order_id' => "$orderId",
        'order_amount' => $amount,
        'order_currency' => $currency,
        'order_description' => $order_description,
        'req_token' => 'N',
        'payer_first_name' => $user_name,
        'payer_last_name' => $user_name,
        'payer_address' => $email,
        'payer_country' => 'SA',
        'payer_city' => 'Riyadh',
        'payer_zip' => '12221',
        'payer_email' => $email,
        'payer_phone' => '966525789635',
        'payer_ip' => '127.0.0.1',
        'term_url_3ds' => $success_url . ''. $orderId,
        'auth' => 'N',
        'recurring_init' => 'N',
        'hash' => $hash,
    );

    $getter = curl_init('https://api.edfapay.com/payment/initiate'); //init curl
    curl_setopt($getter, CURLOPT_POST, 1); //post
    curl_setopt($getter, CURLOPT_POSTFIELDS, $main_req);
    curl_setopt($getter, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($getter);
    $httpcode = curl_getinfo($getter, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    return $result->redirect_url;

}
