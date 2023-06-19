<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SellerCodeCollection;
use App\Http\Resources\Admin\SellerCodeResource;
use App\Models\SellerCode;
use Illuminate\Http\Request;
use Validator;

class SellerCodeController extends Controller
{
    public function index()
    {
        $seller_codes = SellerCode::orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new SellerCodeCollection($seller_codes));
    }
    public function create(Request $request)
    {
        $rules = [
            'marketer_id'      => 'required|exists:marketers,id',
            'country_id'       => 'required|exists:countries,id',
            'seller_name'      => 'required|string|max:191',
            'permanent'        => 'required|in:true,false',
            'status'           => 'required|in:active,finished',
            'percentage'       => 'required|numeric',
            'code_percentage'  => 'required|numeric',
            'start_at'         => 'required|date',
            'end_at'           => 'required|date',
            'type'             => 'required|in:hotel,branch,service,all',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new seller code
        $seller_code = SellerCode::create([
            'marketer_id'        => $request->marketer_id,
            'country_id'         => $request->country_id,
            'package_id'         => 1,
            'seller_name'        => $request->seller_name,
            'permanent'          => $request->permanent,
            'status'             => $request->status,
            'percentage'         => $request->percentage,
            'code_percentage'    => $request->code_percentage,
            'start_at'           => $request->start_at,
            'end_at'             => $request->end_at,
            'type'               => $request->type,
        ]);
        return ApiController::respondWithSuccess(new SellerCodeResource($seller_code));
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'marketer_id'      => 'nullable|exists:marketers,id',
            'country_id'       => 'nullable|exists:countries,id',
            'seller_name'      => 'nullable|string|max:191',
            'permanent'        => 'nullable|in:true,false',
            'status'           => 'nullable|in:active,finished',
            'percentage'       => 'nullable|numeric',
            'code_percentage'  => 'nullable|numeric',
            'start_at'         => 'nullable|date',
            'end_at'           => 'nullable|date',
            'type'             => 'nullable|in:hotel,branch,service,all',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $seller_code = SellerCode::find($id);
        if ($seller_code)
        {
            $seller_code->update([
                'marketer_id'        => $request->marketer_id == null ? $seller_code->marketer_id : $request->marketer_id,
                'country_id'         => $request->country_id == null ? $seller_code->country_id : $request->country_id,
                'package_id'         => 1,
                'seller_name'        => $request->seller_name == null ? $seller_code->seller_name : $request->seller_name,
                'permanent'          => $request->permanent == null ? $seller_code->permanent : $request->permanent,
                'status'             => $request->status == null ? $seller_code->status : $request->status,
                'percentage'         => $request->percentage == null ? $seller_code->percentage : $request->percentage,
                'code_percentage'    => $request->code_percentage == null ? $seller_code->code_percentage : $request->code_percentage,
                'start_at'           => $request->start_at == null ? $seller_code->start_at : $request->start_at,
                'end_at'             => $request->end_at == null ? $seller_code->end_at : $request->end_at,
                'type'               => $request->type == null ? $seller_code->type : $request->type,
            ]);
            return ApiController::respondWithSuccess(new SellerCodeResource($seller_code));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $seller_code = SellerCode::find($id);
        if ($seller_code)
        {
            return ApiController::respondWithSuccess(new SellerCodeResource($seller_code));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $seller_code = SellerCode::find($id);
        if ($seller_code)
        {
            $seller_code->delete();
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
