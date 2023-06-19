<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\AdminNote;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRestaurantNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $notes = AdminNote::whereRestaurantId($id)->get();
        return view('admin.notes.index' , compact('notes' , 'restaurant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($restaurant_id)
    {
        return view('admin.notes.create' , compact('restaurant_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $restaurant_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $this->validate($request , [
            'note'  => 'required|string'
        ]);
        // create new admin note
        AdminNote::create([
            'admin_id'      => Auth::guard('admin')->user()->id,
            'restaurant_id' => $restaurant->id,
            'note'          => $request->note,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('adminNote.index' , $restaurant_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
