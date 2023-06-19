<?php

namespace App\Http\Controllers\AdminController;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\CountryPackage;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Package;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\SellerCode;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function my_profile()
    {

        $data = Admin::find(Auth::id());
        return view('admin.admins.profile.profile', compact('data'));

    }

    public function my_profile_edit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins,email,' . Auth::id(),
//            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);
        $data = Admin::where('id', Auth::id())->update(['name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);
        return redirect(url('/admin/profile'))->with('msg', 'تم التعديل بنجاح');

    }

    public function change_pass()
    {

        return view('admin.admins.profile.change_pass');

    }

    public function change_pass_update(Request $request)
    {
        $this->validate($request, [

            'password' => 'required|string|min:6|confirmed',

        ]);


        $updated = Admin::where('id', Auth::id())->update([
            'password' => Hash::make($request->password)
        ]);
        if(!empty($request->password)):
            Auth::guard('admin')->logoutOtherDevices($request->password);
        endif;
        return redirect(url('/admin/profileChangePass'))->with('msg', 'تم التعديل بنجاح');
    }

    public function index()
    {


        $data = Admin::all();
        return view('admin.admins.admins.index', compact('data'));

    }

    public function create()
    {

        return view('admin.admins.admins.create');

    }

    public function edit($id)
    {
        $data = Admin::find($id);
        return view('admin.admins.admins.edit', compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
//            'password_confirm' => 'required_with:password|same:password|min:4',
            'phone' => 'required',
        ]);


        $request['remember_token'] = Str::random(60);
        $request['password'] = Hash::make($request->password);
        Admin::create($request->all());
        flash(trans('messages.created'))->success();
        return redirect(url('/admin/admins'))->with('msg', 'تم الاضافه بنجاح');
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::where('id', $id)->firstOrFail();
//        dd($request->role);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            //    'password_confirm' => 'nullable|same:password|min:4',
            'phone' => 'required',
            'role' => 'sometimes',
        ]);
        $data = $request->only(['name' , 'email' , 'phone' , 'role']);
        if(!empty($request->password)):
            $data['password'] = Hash::make($request->password);
        endif;

        $data['remember_token'] = Str::random(60);
//        $request['password'] = Hash::make($request->password);

        $admin->update($data);
        if(!empty($request->password)):
            // return $admin;
            Auth::guard('admin')->logoutOtherDevices2($admin , $request->password);
        endif;
        return redirect(url('/admin/admins'))->with('msg', 'تم التعديل بنجاح');
    }

    public function admin_delete($id)
    {
        Admin::where('id', $id)->delete();
        flash(trans('messages.deleted'))->success();
        return back()->with('msg', 'تم الحذف بنجاح');
    }


    public function renew_subscription($id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $admin = $admin == null ? 'restaurant' : 'admin';
        return view('restaurant.user.subscription', compact('user' , 'admin'));
    }

    public function store_subscription(Request $request, $id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $this->validate($request, [
            'payment_method' => 'required|in:bank,online',
            'payment_type' => 'sometimes|in:visa,mada,apple_pay',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',

        ]);
        if ($request->payment == 'true') {
            $user->subscription->update([
                'payment' => 'true'
            ]);
        }
        // get the package price
        $check_price = CountryPackage::whereCountry_id($user->country_id)
            ->wherePackageId(1)
            ->first();
        $tax = Setting::find(1)->tax;
        $discount = 0;
        if ($check_price == null) {
            $package_actual_price = Package::find(1)->price;
        } else {
            $package_actual_price = $check_price->price;
        }
        // check if there are a seller code or not
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('active', 'true')
                ->where('country_id' , $user->country_id)
                ->whereIn('type', ['restaurant' , 'both'])
                //    ->where('discount' , 'subscription')
                ->first();
            if ($seller_code)
            {
                if ($seller_code->start_at <= Carbon::now() && $seller_code->end_at >= Carbon::now())
                {
                    $package_price = $package_actual_price;
                    $discount_percentage = $seller_code->code_percentage;
                    $discount = ($package_price * $discount_percentage) / 100;
                    $price_after_percentage = $package_price - $discount;
                    $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                    $seller_code->update([
                        'commission' => $commission + $seller_code->commission,
                    ]);
                    // store this operation to marketer history
                    MarketerOperation::create([
                        'marketer_id' => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $user->subscription->id,
                        'status' => 'not_done',
                        'amount' => $commission,
                    ]);
                    $price = $price_after_percentage;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                }else{
                    $price = $package_actual_price;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                }
            }else{
                $price = $package_actual_price;
                $tax_value = $price * $tax / 100;
                $price = $price + $tax_value;
            }
        } else {
            $price = $package_actual_price;
            $tax_value = $price * $tax / 100;
            $price = $price + $tax_value;
        }
        $user->subscription->update([
            'package_id' => 1,
            'bank_id' => $request->bank_id,
            'payment_type' => $request->payment_method,
            'price' => $price,
            'tax_value' => $tax_value,
            'discount_value' => $discount,
        ]);

        if ($request->payment_method == 'bank') {
            $banks = Bank::whereCountryId($user->country_id)
                ->where('restaurant_id' , null)
                ->get();
            $admin = $admin == null ? 'restaurant' : $admin;
            return view('restaurant.user.payments.bank_transfer', compact('user', 'tax','tax_value','discount','banks' , 'admin'));
        }
        else {
            // online payment By My fatoorah
            $branch = Branch::whereRestaurantId($id)
                ->where('main' , 'true')
                ->first();
            $amount = check_restaurant_amount($branch->id , $price);
            if ($request->payment_type == 'visa') {
                $charge = 2;
            } elseif ($request->payment_type == 'mada') {
                $charge = 6;
            } elseif ($request->payment_type == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = $user->name_en;
            $token = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $user->country->code,
                'CustomerMobile' => $user->phone_number,
                'CustomerEmail' => $user->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => route('checkRestaurantStatus' , $admin),
                'ErrorUrl' => url('/error'),
                'Language' => app()->getLocale(),
                'CustomerReference' => 'ref 1',
                'CustomerCivilId' => '12345678',
                'UserDefinedField' => 'Custom field',
                'ExpireDate' => '',
                'CustomerAddress' => array(
                    'Block' => '',
                    'Street' => '',
                    'HouseBuildingNo' => '',
                    'Address' => '',
                    'AddressInstructions' => '',
                ),
                'InvoiceItems' => [array(
                    'ItemName' => $name,
                    'Quantity' => '1',
                    'UnitPrice' => $amount,
                )],
            );
            $data = json_encode($data);
            $fatooraRes = MyFatoorah($token, $data);
            $result = json_decode($fatooraRes);
            if ($result != null) {
                if ($result->IsSuccess === true) {
                    $user->subscription->update([
                        'invoice_id' => $result->Data->InvoiceId,
                    ]);
                    return redirect()->to($result->Data->PaymentURL);
                } else {
                    return redirect()->to(url('/error'));
                }
            } else {
                return redirect()->to(url('/error'));
            }
        }
    }

    public function check_status(Request $request , $admin = null)
    {

        $token = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $subscription = Subscription::where('invoice_id', $InvoiceId)->first();
            $end_at = Carbon::now()->addMonths($subscription->package->duration);
            $branch = Branch::whereRestaurantId($subscription->restaurant_id)
                ->whereMain('true')
                ->first();
            if ($subscription->status == 'finished' or $subscription->status == 'active')
            {
                // create report as renewed
                Report::create([
                    'restaurant_id'  => $subscription->restaurant_id,
                    'branch_id'      => $subscription->branch_id,
                    'seller_code_id' => $subscription->seller_code_id,
                    'amount'         => $subscription->restaurant->country_id == 2 ? $subscription->price : check_restaurant_amount($branch->id , $subscription->price),
                    'status'         => 'renewed',
                    'type'           => $subscription->type == 'restaurant' ? 'restaurant' : 'branch',
                    'invoice_id'     => $InvoiceId,
                    'discount'       => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
                History::create([
                    'restaurant_id' => $subscription->restaurant->id,
                    'package_id' => $subscription->package->id,
                    'branch_id' => $subscription->branch_id,
                    'operation_date' => Carbon::now(),
                    'details' =>   $subscription->type == 'restaurant' ?'تجديد اشتراك المطعم':'تجديد اشتراك الفرع',
                    'payment_type' => 'online',
                    'invoice_id' => $subscription->invoice_id,
                    'paid_amount' => $subscription->restaurant->country_id == 2 ? $subscription->price : check_restaurant_amount($branch->id , $subscription->price),
                    'discount_value' => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
            }else{
                // create report as subscribed
                Report::create([
                    'restaurant_id'  => $subscription->restaurant_id,
                    'branch_id'      => $subscription->branch_id,
                    'seller_code_id' => $subscription->seller_code_id,
                    'amount'         => $subscription->restaurant->country_id == 2 ? $subscription->price : check_restaurant_amount($branch->id , $subscription->price),
                    'status'         => 'subscribed',
                    'type'           => $subscription->type == 'restaurant' ? 'restaurant' : 'branch',
                    'invoice_id'     => $InvoiceId,
                    'discount'       => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
                History::create([
                    'restaurant_id' => $subscription->restaurant->id,
                    'package_id' => $subscription->package->id,
                    'branch_id' => $subscription->branch_id,
                    'operation_date' => Carbon::now(),
                    'details' =>   $subscription->type == 'restaurant' ?' اشتراك مطعم جديد':' اشتراك فرع جديد',
                    'payment_type' => 'online',
                    'invoice_id' => $subscription->invoice_id,
                    'paid_amount' => $subscription->restaurant->country_id == 2 ? $subscription->price : check_restaurant_amount($branch->id , $subscription->price),
                    'discount_value' => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
            }
            $subscription->update([
                'status' => 'active',
                'end_at' => $end_at,
            ]);

            if ($subscription->type == 'restaurant') {
                $subscription->restaurant->update([
                    'status' => 'active',
                    'admin_activation' => 'true',
                ]);

                // update the main branch
                $main_branch = Branch::whereRestaurantId($subscription->restaurant->id)
                    ->where('main', 'true')
                    ->first();
                $main_branch->update([
                    'status' => 'active',
                ]);
                $main_branch->subscription->update([
                    'status' => 'active',
                    'end_at' => $end_at,
                ]);

                $operation = MarketerOperation::whereSubscriptionId($subscription->id)
                    ->where('status', 'not_done')
                    ->first();
                if ($operation) {
                    $operation->update([
                        'status' => 'done',
                    ]);
                    $balance = $operation->marketer->balance + $operation->amount;
                    $operation->marketer->update([
                        'balance' => $balance
                    ]);
                    $subscription->update(['seller_code_id' => $operation->seller_code_id]);
                }

            }
            flash(trans('messages.onlinePaymentDone'))->success();
            if ($subscription->branch != null) {
                $subscription->branch->update([
                    'status' => 'active',
                ]);
            }
            if ($subscription->type == 'restaurant') {
                if ($admin == 'admin')
                {
                    return redirect()->route('showRestaurant' , $subscription->restaurant->id);
                }else{
                    return redirect()->route('RestaurantProfile');
                }
            } else {
                return redirect()->route('branches.index');
            }
        }
    }
    public function renewSubscriptionBank(Request $request, $id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $this->validate($request, [
            'bank_id' => 'required|exists:banks,id',
            'transfer_photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        // update user subscription
        $user->subscription->update([
            'bank_id' => $request->bank_id,
            'transfer_photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
        ]);
//        flash(trans('messages.bankTransferDone'))->success();
        if ($admin == 'restaurant')
        {
            return redirect()->route('RestaurantProfile');
        }elseif ($admin == 'admin'){
            return redirect()->route('showRestaurant' , $user->id);
        }
    }

    public function get_branch_payment($id , $type = null)
    {
        $branch = Branch::findOrFail($id);
        $type = $type != null  ? 'admin' : 'restaurant';
        return view('restaurant.branches.subscription', compact('branch' , 'type'));
    }

    public function store_branch_payment(Request $request, $id , $type = null)
    {
        if (!auth('admin')->check()):
            return redirect(url('admin/login'));
        endif;
        $this->validate($request, [
            'payment_method' => 'required|in:bank,online',
            'payment_type' => 'sometimes|in:visa,mada,apple_pay',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',

        ]);
        $branch = Branch::findOrFail($id);
        if ($request->payment == 'true') {
            $branch->subscription->update([
                'payment' => 'true'
            ]);
        }
        // create subscription for branch
        $package = Package::find(1);
        $check_price = CountryPackage::whereCountry_id($branch->country_id)
            ->wherePackageId($package->id)
            ->first();
        if ($check_price == null) {
            $package_actual_price = $branch->main == 'true' ? $package->price : $package->branch_price;
        } else {
            $package_actual_price = $branch->main == 'true' ? $check_price->price : $check_price->branch_price;
        }
        $discount = 0;
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('active', 'true')
                ->where('country_id', $branch->country_id)
                ->whereIn('type', ['branch', 'both'])
                ->first();
            if ($seller_code) {
                if ($seller_code->start_at <= Carbon::now() && $seller_code->end_at >= Carbon::now()) {
                    $price = $package_actual_price;
                    $package_price = $price;
                    $discount_percentage = $seller_code->code_percentage;
                    $discount = ($package_price * $discount_percentage) / 100;
                    $price_after_percentage = $package_price - $discount;
                    $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                    $total_commission = $seller_code->commission + $commission;
                    $seller_code->update([
                        'commission' => $total_commission,
                    ]);
                    // store this operation to marketer history
                    MarketerOperation::create([
                        'marketer_id' => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $branch->subscription->id,
                        'status' => 'not_done',
                        'amount' => $commission,
                    ]);
                    $price = $price_after_percentage;
                } else {
                    $price = $package_actual_price;
                }
            } else {
                $price = $package_actual_price;
            }
        } else {
            $price = $package_actual_price;
        }
        // add the tax for branch subscription price
        $tax = Setting::find(1)->tax;
        $tax_value = $price * $tax / 100;
        $price = $price + $tax_value;
        // check branch has subscription or not
        $subscription_check = Subscription::whereRestaurantId($branch->restaurant->id)
            ->where('branch_id', $branch->id)
            ->first();
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('active', 'true')
                ->where('country_id', $branch->country_id)
                ->whereIn('type', ['branch', 'both'])
                ->first();
        } else {
            $seller_code = null;
        }
        if ($subscription_check != null) {
            $subscription_check->update([
                'package_id' => $package->id,
                'price' => $price,
                'tax_value' => $tax_value,
                'discount_value' => $discount,
                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                'payment_type' => $request->payment_method,
            ]);
            $subscription = $subscription_check;
        } else {

            $subscription = Subscription::create([
                'package_id' => $package->id,
                'restaurant_id' => $branch->restaurant->id,
                'branch_id' => $branch->id,
                'price' => $price,
                'status' => 'tentative',
                'type' => 'branch',
                'tax_value' => $tax_value,
                'discount_value' => $discount,
                'payment_type' => $request->payment_method,
            ]);
        }
        if ($request->payment_method == 'bank') {
            $type = $type != null  ? 'admin' : 'restaurant';
            return redirect()->route('renewSubscriptionBranchBank', [$branch->restaurant->id, $branch->country_id, $subscription->id , $type]);
        } else {
            // online payment By My fatoorah
            $amount = check_restaurant_amount($id, $price);
            if ($request->payment_type == 'visa') {
                $charge = 2;
            } elseif ($request->payment_type == 'mada') {
                $charge = 6;
            } elseif ($request->payment_type == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = app()->getLocale() == 'ar' ? $branch->restaurant->name_ar : $branch->restaurant->name_en;
            $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $branch->restaurant->country->code,
                'CustomerMobile' => $branch->restaurant->phone_number,
                'CustomerEmail' => $branch->restaurant->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => route('checkRestaurantStatus'),
                'ErrorUrl' => url('/error'),
                'Language' => app()->getLocale(),
                'CustomerReference' => 'ref 1',
                'CustomerCivilId' => '12345678',
                'UserDefinedField' => 'Custom field',
                'ExpireDate' => '',
                'CustomerAddress' => array(
                    'Block' => '',
                    'Street' => '',
                    'HouseBuildingNo' => '',
                    'Address' => '',
                    'AddressInstructions' => '',
                ),
                'InvoiceItems' => [array(
                    'ItemName' => $name,
                    'Quantity' => '1',
                    'UnitPrice' => $amount,
                )],
            );
            $data = json_encode($data);
            $fatooraRes = MyFatoorah($token, $data);
            $result = json_decode($fatooraRes);
            if ($result->IsSuccess === true) {
                $subscription->update([
                    'invoice_id' => $result->Data->InvoiceId,
                ]);
                return redirect()->to($result->Data->PaymentURL);
            } else {
                return redirect()->to(url('/error'));
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->back();
    }

    public function renewSubscriptionBankGet($id, $country_id, $subscription_id , $type = null)
    {
        $user = Restaurant::findOrFail($id);
        $banks = Bank::whereCountryId($country_id)
            ->where('restaurant_id', null)
            ->get();
        $subscription = Subscription::find($subscription_id);
        $type = $type != null  ? 'admin' : 'restaurant';
        return view('restaurant.branches.payments.bank_transfer', compact('user', 'banks', 'subscription' , 'type'));

    }
    public function renewBranchByBank(Request $request, $id)
    {
        if (!auth('admin')->check()):
            return redirect(url('admin/login'));
        endif;
        $subscription = Subscription::findOrFail($id);
        $this->validate($request, [
            'bank_id' => 'required|exists:banks,id',
            'transfer_photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        // update user subscription
        $subscription->update([
            'bank_id' => $request->bank_id,
            'transfer_photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
        ]);
        $isNew = true;
        if (History::where('branch_id', $subscription->branch_id)->where('restaurant_id', $subscription->restaurant->id)->count() > 0) $isNew = false;

        flash(trans('messages.bankTransferDone'))->success();
        return redirect()->to(url('/admin/branches/active'));
    }


}
