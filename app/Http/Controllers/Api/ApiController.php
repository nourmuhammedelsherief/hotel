<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ApiController extends Controller
{

    public function contactUs(Request $request) {

        $rules = [
            'name'      => 'required|max:255',
            'email'     => 'required|max:194',
            'message'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return $this->respondWithError(validateRules($validator->errors(), $rules));

        $created = ContactUs::create($request->all());

        return $created
            ? $this->respondWithSuccess($created)
            : $this->respondWithServerError();
    }

    public function listNotifications(Request $request) {

        $notifications = Notification::Where('user_id', $request->user()->id)
            ->orderBy('id','desc')
            ->get();
        if ($notifications->count() > 0)
        {
            return $this->respondWithSuccessData(NotificationResource::collection($notifications));
        }else{
            $errors = [
                'message'  => trans('messages.no_notifications')
            ];
            return $this->respondWithErrorArray(array($errors));
        }

    }
    public function delete_Notifications( $id , Request $request) {

        $data = Notification::Where('id', $id)->where('user_id',$request->user()->id)->delete();
        $errors = [
            'message'  => trans('messages.no_notification')
        ];
        return $data
            ? $this->respondWithSuccess([
                'message'=> trans('messages.notification_deleted')
            ])
            : $this->respondWithErrorArray(array($errors));
    }

    public static function createUserDeviceToken($userId, $deviceToken) {

        $created = UserDevice::updateOrCreate(
            ['user_id' => $userId]
            ,['device_token' => $deviceToken]);
        return $created;
    }
    public static function createDeviceToken($deviceToken) {

        $created = UserDevice::updateOrCreate([
            'device_token' => $deviceToken
        ]);
        return $created;
    }
    public static function respondWithSuccess($data) {
        http_response_code(200);
        return response()->json($data)->setStatusCode(200);
    }
    public static function respondWithSuccessData($data) {
        http_response_code(200);
        return response()->json(['data' => $data])->setStatusCode(200);
    }

    public static function respondWithErrorArray($errors) {
        http_response_code(422);  // set the code
        return response()->json(['errors' => $errors])->setStatusCode(422);
    }public static function respondWithErrorObject($errors) {
    http_response_code(422);  // set the code
    return response()->json($errors)->setStatusCode(422);
}
    public static function respondWithErrorNOTFoundObject($errors) {
        http_response_code(404);  // set the code
        return response()->json($errors)->setStatusCode(404);
    }
    public static function respondWithErrorNOTFoundArray($errors) {
        http_response_code(404);  // set the code
        return response()->json(['mainCode'=> 0,'code' =>  http_response_code()  , $errors])->setStatusCode(404);
    }
    public static function respondWithErrorClient($errors) {
        http_response_code(400);  // set the code
        return response()->json($errors)->setStatusCode(400);
    }
    public static function respondWithErrorAuthObject($errors) {
        http_response_code(401);  // set the code
        return response()->json($errors)->setStatusCode(401);
    }
    public static function respondWithErrorAuthArray($errors) {
        http_response_code(401);  // set the code
        return response()->json($errors)->setStatusCode(401);
    }


    public static function respondWithServerErrorArray() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['error'=>$errors])->setStatusCode(500);
    }
    public static function respondWithServerErrorObject() {
        $errors = 'Sorry something went wrong, please try again';
        http_response_code(500);
        return response()->json(['error' => $errors])->setStatusCode(500);
    }



}
