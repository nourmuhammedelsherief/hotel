<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\HotelCollection;
use App\Http\Resources\Admin\HotelResource;
use App\Models\Branch;
use App\Models\Hotel;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\FlareClient\Api;
use Validator;

class HotelController extends Controller
{
    public function index($status)
    {
        if ($status == 'waiting_activation')
        {
            $hotels = Hotel::whereAdminActivation('false')
                ->paginate();
        }elseif ($status == 'less_30_day')
        {
            $hotels = Hotel::whereHas('subscription' , function ($q) use ($status){
                $q->whereStatus('active');
                $q->whereDate('end_at' , '<' , Carbon::now()->addDays(30));
            })
                ->whereAdminActivation('true')
                ->whereArchive('false')
                ->paginate();
        }elseif ($status == 'archived'){
            $hotels = Hotel::whereArchive('true')
                ->paginate();
        }else{
            $hotels = Hotel::whereHas('subscription' , function ($q) use ($status){
                $q->whereStatus($status);
            })
                ->whereAdminActivation('true')
                ->whereArchive('false')
                ->paginate();
        }
        return ApiController::respondWithSuccess(new HotelCollection($hotels));
    }
    public function create(Request $request)
    {
        $rules = [
            'name_ar'    => 'required|string|max:191',
            'name_en'    => 'required|string|max:191',
            'subdomain'  => 'required|string|unique:hotels',
            'logo'       => 'sometimes|mimes:jpg,jpeg,gif,tif,psd,pmp,png|max:5000',
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'email'      => 'required|email|unique:hotels',
            'password'   => 'required|min:6',
            'password_confirmation' => 'required|same:password|min:6',
            'phone_number'  => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new Hotel
        $hotel = Hotel::create([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'subdomain'     => $request->subdomain,
            'status'        => 'tentative',
            'logo'          => $request->file('logo') == null ? null : UploadImage($request->file('logo') , 'logo' , '/uploads/logo'),
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'package_id'    => 1,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone_number'  => $request->phone_number,
            'phone_verified_at' => Carbon::now(),
            'admin_activation'  => 'true',
        ]);

        // create hotel main branch
        $branch = Branch::create([
            'hotel_id'   => $hotel->id,
            'country_id' => $hotel->country_id,
            'city_id'    => $hotel->city_id,
            'status'     => 'tentative',
            'main'       => 'true',
            'name_ar'    => $hotel->name_ar,
            'name_en'    => $hotel->name_en,
            'subdomain'  => $hotel->subdomain,
            'email'      => $hotel->email,
            'phone_number' => $hotel->phone_number,
            'password'     => $hotel->password,
        ]);
        // create hotel subscription
        $tentative_period = Setting::first()->tentative_period;
        Subscription::create([
            'hotel_id'          => $hotel->id,
            'branch_id'         => $branch->id,
            'package_id'        => 1,
            'type'              => 'hotel',
            'status'            => 'tentative',
            'subscription_type' => 'subscription',
            'end_at'            => Carbon::now()->addDays($tentative_period),
            'is_payment'        => 'false',
        ]);
        return  ApiController::respondWithSuccess(new HotelResource($hotel));
    }
    public function edit(Request $request , $id)
    {
        $rules = [
            'name_ar'    => 'sometimes|string|max:191',
            'name_en'    => 'sometimes|string|max:191',
            'subdomain'  => 'sometimes|string|unique:hotels,subdomain,'.$id,
            'logo'       => 'sometimes|mimes:jpg,jpeg,gif,tif,psd,pmp,png|max:5000',
            'country_id' => 'sometimes|exists:countries,id',
            'city_id'    => 'sometimes|exists:cities,id',
            'email'      => 'sometimes|email|unique:hotels,email,'.$id,
            'password'   => 'sometimes|min:6',
            'password_confirmation' => 'sometimes|same:password|min:6',
            'phone_number'  => 'sometimes|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = Hotel::find($id);
        if ($hotel)
        {
            $hotel->update([
                'name_ar'       => $request->name_ar == null ? $hotel->name_ar : $request->name_ar,
                'name_en'       => $request->name_en == null ? $hotel->name_en : $request->name_en,
                'subdomain'     => $request->subdomain == null ? $hotel->subdomain : $request->subdomain,
                'logo'          => $request->file('logo') == null ? $hotel->logo : UploadImageEdit($request->file('logo') , 'logo' , '/uploads/logo' , $hotel->logo),
                'country_id'    => $request->country_id == null ? $hotel->country_id : $request->country_id,
                'city_id'       => $request->city_id == null ? $hotel->city_id : $request->city_id,
                'email'         => $request->email == null ? $hotel->email : $request->email,
                'password'      => $request->password == null ? $hotel->password : Hash::make($request->password),
                'phone_number'  => $request->phone_number == null ? $hotel->phone_number : $request->phone_number,
            ]);
            // update hotel main branch
            $branch = Branch::whereHotelId($hotel->id)
                ->whereMain('true')
                ->first();
            if ($branch)
            {
                $branch->update([
                    'hotel_id'   => $hotel->id,
                    'country_id' => $hotel->country_id,
                    'city_id'    => $hotel->city_id,
                    'name_ar'    => $hotel->name_ar,
                    'name_en'    => $hotel->name_en,
                    'subdomain'  => $hotel->subdomain,
                    'email'      => $hotel->email,
                    'phone_number' => $hotel->phone_number,
                    'password'     => $hotel->password,
                ]);
            }
            return  ApiController::respondWithSuccess(new HotelResource($hotel));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);
        if ($hotel)
        {
            return ApiController::respondWithSuccess(new HotelResource($hotel));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        if ($hotel)
        {
            if (isset($hotel->logo))
            {
                @unlink(public_path('/uploads/logo/' . $hotel->logo));
            }
            $hotel->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function archive(Request $request , $id)
    {
        $rules = [
            'archive'    => 'required|in:true,false',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $hotel = Hotel::find($id);
        if ($hotel)
        {
            $hotel->update([
                'archive' => $request->archive,
            ]);
            $success = [
                'message' => trans('messages.data_changed_successfully')
            ];
            return  ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function active_hotel($id)
    {
        $hotel = Hotel::find($id);
        if ($hotel)
        {
            $hotel->update([
                'admin_activation' => 'true',
            ]);
            $success = [
                'message' => trans('messages.data_changed_successfully')
            ];
            return  ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }

}
