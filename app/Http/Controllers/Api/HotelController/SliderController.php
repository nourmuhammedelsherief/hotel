<?php

namespace App\Http\Controllers\Api\HotelController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Hotel\HotelSliderCollection;
use App\Http\Resources\Hotel\HotelSliderResource;
use App\Models\HotelSlider;
use App\Models\HotelSliderImage;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;
use Validator;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $hotel = $request->user();
        $sliders = $hotel->sliders()->orderBy('id' , 'desc')->paginate();
        return ApiController::respondWithSuccess(new HotelSliderCollection($sliders));
    }
    public function create(Request $request)
    {
        $hotel = $request->user();
        $rules = [
            'type'     => 'required|in:image,video,youtube,gif',
            'gif'      => 'required_if:type,gif|mimes:gif|max:8000',
            'youtube'  => 'required_if:type,youtube|max:191',
            'video'    => 'required_if:type,video|mimes:mp4,flv,m3u8,ts,3gp,mov,avi,wmv|max:10000',
            'images'   => 'required_if:type,image',
//            'images'   => 'mimes:jpg,jpeg,gif,tif,psd,pmp,png,webp|max:5000',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        $slider = HotelSlider::create([
            'hotel_id'  => $hotel->id,
            'type'      => $request->type,
            'gif'       => $request->file('gif') == null ? null : UploadVideo($request->file('gif') , '/uploads/gifs'),
            'youtube'   => $request->youtube == null ? null : $request->youtube,
            'video'     => $request->video == null ? null : UploadVideo($request->file('video') , '/uploads/slider_videos/'),
        ]);
        if ($request->type == 'image')
        {
            // create slider images
            if ($request->images != null)
            {
                foreach ($request->images as $image)
                {
                    HotelSliderImage::create([
                        'slider_id' => $slider->id,
                        'image'     => $image == null ? null : UploadImage($image , 'gif' , '/uploads/slider_images'),
                    ]);
                }
            }
        }
        return ApiController::respondWithSuccess(new HotelSliderResource($slider));
    }
    public function edit(Request $request , $id)
    {
        $hotel = $request->user();
        $slider = HotelSlider::find($id);
        if ($slider)
        {
            $rules = [
                'type'     => 'required|in:image,video,youtube,gif',
                'gif'      => 'required_if:type,gif|mimes:gif|max:8000',
                'youtube'  => 'required_if:type,youtube|max:191',
                'video'    => 'required_if:type,video|mimes:mp4,flv,m3u8,ts,3gp,mov,avi,wmv|max:10000',
                'images'   => 'required_if:type,image',
//            'images'   => 'mimes:jpg,jpeg,gif,tif,psd,pmp,png,webp|max:5000',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

            $slider->update([
                'hotel_id'  => $hotel->id,
                'type'      => $request->type,
                'gif'       => $request->file('gif') == null ? $slider->gif : UploadVideoEdit($request->file('gif') , '/uploads/gifs/' , $slider->gif),
                'youtube'   => $request->youtube == null ? $slider->youtube : $request->youtube,
                'video'     => $request->video == null ? $slider->video : UploadVideoEdit($request->file('video') , '/uploads/slider_videos/' , $slider->video),
            ]);
            if ($request->type == 'image')
            {
                // create slider images
                if ($request->images != null)
                {
                    foreach ($request->images as $image)
                    {
                        HotelSliderImage::create([
                            'slider_id' => $slider->id,
                            'image'     => $image == null ? null : UploadImage($image , 'gif' , '/uploads/slider_images'),
                        ]);
                    }
                }
            }
            return ApiController::respondWithSuccess(new HotelSliderResource($slider));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function show($id)
    {
        $slider = HotelSlider::find($id);
        if ($slider)
        {
            return ApiController::respondWithSuccess(new HotelSliderResource($slider));
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function destroy($id)
    {
        $slider = HotelSlider::find($id);
        if ($slider)
        {
            if ($slider->type == 'image')
            {
                if ($slider->images->count() > 0)
                {
                    foreach ($slider->images as $image)
                    {
                        if ($image->image != 'slider1.jpg' and $image->image != 'slider2.jpg')
                        {
                            @unlink(public_path('/uploads/slider_images/' . $image->image));
                        }
                    }
                }
            }elseif ($slider->type == 'gif')
            {
                if ($slider->gif != null)
                {
                    @unlink(public_path('/uploads/gifs/' . $slider->gif));
                }
            }elseif ($slider->type == 'video')
            {
                if ($slider->video != null)
                {
                    @unlink(public_path('/uploads/slider_videos/' . $slider->video));
                }
            }
            $slider->delete();
            $success = [
                'message' => trans('messages.deleted')
            ];
            return ApiController::respondWithSuccess($success);
        }else{
            $error = ['message' => trans('messages.not_found')];
            return ApiController::respondWithErrorNOTFoundObject($error);
        }
    }
    public function delete_slider_image($id)
    {
        $image = HotelSliderImage::find($id);
        if ($image)
        {
            if ($image->image != 'slider1.jpg' and $image->image != 'slider2.jpg')
            {
                @unlink(public_path('/uploads/slider_images/' . $image->image));
            }
            $image->delete();
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
