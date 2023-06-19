<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MarketerCollection;
use App\Http\Resources\Admin\MarketerResource;
use App\Models\Marketer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class MarketerController extends Controller
{
    public function index()
    {
        $marketers = Marketer::orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new MarketerCollection($marketers));
    }
    public function create(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|max:191',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new marketer
        $marketer = Marketer::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);
        return ApiController::respondWithSuccess(new MarketerResource($marketer));
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'name'     => 'sometimes|string|max:191',
            'email'    => 'sometimes|email|max:191',
            'password' => 'sometimes|string|min:6',
            'password_confirmation' => 'sometimes|same:password'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $marketer = Marketer::find($id);
        if ($marketer)
        {
            $marketer->update([
                'name'      => $request->name == null ? $marketer->name : $request->name,
                'email'     => $request->email == null ? $marketer->email : $request->email,
                'password'  => $request->password == null ? $marketer->password : Hash::make($request->password),
            ]);
            return ApiController::respondWithSuccess(new MarketerResource($marketer));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $marketer = Marketer::find($id);
        if ($marketer)
        {
            return ApiController::respondWithSuccess(new MarketerResource($marketer));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $marketer = Marketer::find($id);
        if ($marketer)
        {
            $marketer->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
}
