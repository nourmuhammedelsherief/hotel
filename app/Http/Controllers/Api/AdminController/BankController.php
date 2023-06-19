<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BankCollection;
use App\Http\Resources\Admin\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;
use Validator;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::paginate();
        return ApiController::respondWithSuccess(new BankCollection($banks));
    }
    public function create(Request $request)
    {
        $rules = [
            'country_id'  => 'required|exists:countries,id',
            'name_ar'     => 'required|string|max:191',
            'name_en'     => 'required|string|max:191',
            'account_number' => 'required',
            'iban_number'  => 'sometimes',
            'useful'      => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new bank
        $bank = Bank::create([
            'country_id' => $request->country_id,
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
            'account_number' => $request->account_number,
            'iban_number' => $request->iban_number == null ? null : $request->iban_number,
            'useful'  => $request->useful == null ? null : $request->useful,
        ]);
        return ApiController::respondWithSuccess(new BankResource($bank));
    }
    public function show($id)
    {
        $bank = Bank::find($id);
        if ($bank)
        {
            return ApiController::respondWithSuccess(new BankResource($bank));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'country_id'  => 'required|exists:countries,id',
            'name_ar'     => 'required|string|max:191',
            'name_en'     => 'required|string|max:191',
            'account_number' => 'required',
            'iban_number'  => 'sometimes',
            'useful'      => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $bank = Bank::find($id);
        if ($bank)
        {
            $bank->update([
                'country_id' => $request->country_id,
                'name_ar'    => $request->name_ar,
                'name_en'    => $request->name_en,
                'account_number' => $request->account_number,
                'iban_number' => $request->iban_number == null ? null : $request->iban_number,
                'useful'  => $request->useful == null ? null : $request->useful,
            ]);
            return ApiController::respondWithSuccess(new BankResource($bank));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function destroy($id)
    {
        $bank = Bank::find($id);
        if ($bank)
        {
            $bank->delete();
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
