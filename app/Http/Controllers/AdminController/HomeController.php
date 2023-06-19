<?php

namespace App\Http\Controllers\AdminController;

use App\City;
use App\Food;
use App\FoodRequest;
use App\Http\Controllers\Controller;
use App\Models\CategoryItem;
use App\Models\DiwanCategory;
use App\Models\Download;
use App\Models\DownloadItem;
use App\Models\Link;
use App\Models\Service;
use App\Models\Sound;
use App\Models\Team;
use App\Order;
use App\User;
use App\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = DB::table('admins')->count();
        return view('admin.home' , compact('admins'));
    }
}
