<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\FlareClient\Api;
use Validator;

class AuthAdminController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required',
//            'device_token' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $admin = Admin::whereEmail($request->email)->first();
        if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $admin->update([
                'api_token' => generateApiToken($admin->id, 50),
            ]);
            return ApiController::respondWithSuccess(new AdminResource($admin));
        } else {
            if ($admin == null) {
                $errors = [
                    'message' => trans('messages.wrong_email'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            } else {
                $errors = [
                    'message' => trans('messages.wrong_password'),
                ];
                return ApiController::respondWithErrorObject(array($errors));
            }
        }

    }
    public function logout(Request $request)
    {
        $admin = Admin::find($request->user()->id);
        $admin->update([
                'api_token' => null
            ]);
        $success = [
            'message' => trans('messages.logout_successfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
    public function changePassword(Request $request)
    {
        $rules = [
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $admin = $request->user();
        $admin->update([
            'password' => Hash::make($request->password)
        ]);
        $success = [
            'message' => trans('messages.password_changed_successfully')
        ];
        return ApiController::respondWithSuccess($success);
    }
    public function profile(Request $request)
    {
        $admin = $request->user();
        return ApiController::respondWithSuccess(new AdminResource($admin));
    }
    public function edit_account(Request $request)
    {
        $rules = [
            'name'     => 'sometimes|string|max:191',
            'email'    => 'sometimes|email|max:191',
            'phone'    => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $admin = $request->user();
        $admin->update([
            'name'   => $request->name == null ? $admin->name : $request->name,
            'email'  => $request->email == null ? $admin->email : $request->email,
            'phone'  => $request->phone == null ? $admin->phone : $request->phone,
        ]);
        return ApiController::respondWithSuccess(new AdminResource($admin));
    }
    public function admins()
    {
        $admins = Admin::all();
        return ApiController::respondWithSuccess(AdminResource::collection($admins));
    }
    public function create(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|max:191',
            'phone'    => 'sometimes',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new admin
        $admin = Admin::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
        ]);
        return ApiController::respondWithSuccess(new AdminResource($admin));
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'name'     => 'sometimes|string|max:191',
            'email'    => 'sometimes|email|max:191',
            'phone'    => 'sometimes',
            'password' => 'sometimes|string|min:6',
            'password_confirmation' => 'required_if:password,!=,null|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $admin = Admin::find($id);
        if ($admin)
        {
            $admin->update([
                'name'      => $request->name == null ? $admin->name : $request->name,
                'email'     => $request->email == null ? $admin->email : $request->email,
                'password'  => $request->password == null ? $admin->password : Hash::make($request->password),
                'phone'     => $request->phone == null ? $admin->phone : $request->phone,
            ]);
            return ApiController::respondWithSuccess(new AdminResource($admin));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function delete($id)
    {
        $admin = Admin::find($id);
        if ($admin)
        {
            $admin->delete();
            $success = ['message' => trans('messages.deleted')];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function get_admin($id)
    {
        $admin = Admin::find($id);
        if ($admin)
        {
            return ApiController::respondWithSuccess(new AdminResource($admin));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
