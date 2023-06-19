<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $users = User::whereType($type)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('admin.users.index' , compact('users' , 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('admin.users.create' , compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name'          => 'required|string|max:191',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'phone_number'  => ['required', 'unique:users','regex:/^((05)|(01))[0-9]{8}/' , 'max:11'],
            'city_id'       => 'required|exists:cities,id',
            'type'          => 'required|in:origin,sector',
            'photo'         => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp|max:5000',
        ]);
        // create new  users
        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'city_id'      => $request->city_id,
            'type'         => $request->type,
            'photo'        => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/users'),
        ]);
        flash('تم أضافه المستخدم بنجاح')->success();
        return redirect()->route('User' , $request->type);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $cities = City::all();
        return  view('admin.users.edit' , compact('user' , 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request , [
            'name'          => 'required|string|max:191',
            'email'         => 'required|email|unique:users,email,' . $id,
            'phone_number'  => ['required', 'unique:users,phone_number,'.$id,'regex:/^((05)|(01))[0-9]{8}/' , 'max:11'],
            'city_id'       => 'required|exists:cities,id',
            'type'          => 'required|in:origin,sector',
            'photo'         => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp|max:5000',
        ]);
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'city_id'      => $request->city_id,
            'type'         => $request->type,
            'photo'        => $request->file('photo') == null ? $user->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/users' , $user->photo),
        ]);
        flash('تم تعديل المستخدم بنجاح')->success();
        return redirect()->route('User' , $user->type);
    }

    public function update_pass(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',

        ]);
        $users = User::findOrfail($id);
        $users->password = Hash::make($request->password);

        $users->save();

        return redirect()->back()->with('information', 'تم تعديل كلمة المرور المستخدم');
    }

    public function update_privacy(Request $request, $id)
    {
        $this->validate($request, [
            'active' => 'required',
        ]);
        $users = User::findOrfail($id);
        $users->active = $request->active;
        $users->save();

        return redirect()->back()->with('information', 'تم تعديل اعدادات المستخدم');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->photo != null) {
            if (file_exists(public_path('uploads/users/' . $user->photo))) {
                unlink(public_path('uploads/users/' . $user->photo));
            }
        }
        $user->delete();
        flash('تم الحذف بنجاح')->success();
        return back();
    }


    public function active_user($id, $active)
    {
//        dd($active);
        $user = User::findOrFail($id);
        $user->update([
            'active' => $active
        ]);
        flash('تم تغيير الخصوصية بنجاح')->success();
        return redirect()->back();
    }
}
