<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;

use Auth;

use App\Models\ProductsModel;
use App\Models\CategoriesModel;
use App\Models\CategoryBrands;
use App\Models\ProductsSizes;
use App\Models\ProductSizeTypes;
use App\Models\BrandTypesModel;
use App\Models\BrandsModel;
use App\Models\OrdersModel;
use App\Models\SizeListModel;
use App\Models\SubscriptionsModel;
use App\User;

use App\Traits\CustomFunction;


class DashboardController extends Controller
{
    public function login(LoginRequest $request){
        if(Auth::check()){
            if(Auth::user()->is_admin == 1){
                return redirect('admin/dashboard');
            }else{
                return redirect('/');
            }
        }
        if($request->isMethod('post')){
            $validated = $request->validated();
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1, 'is_admin' =>1])){
                    return redirect('admin/dashboard');
            }else{
                return redirect()->back()->withErrors('Email or Password does not match.');
            }
        }else{
            return view('admin.login');
        }
    }

    public function dashboard(Request $request){
        if(Auth::check()){
            if(Auth::User()->is_admin == 1){
                $userCount = User::where(['is_admin' => '0','status' => 1])->count();
                $productCount = ProductsModel::where('status',1)->count();
                $vipProductCount = ProductsModel::where(['vip_status'=>1,'status'=>'1'])->count();
                $categoryCount = CategoriesModel::where('status',1)->count();
                $brandCount = BrandsModel::where('status',1)->count();
                $orderCount = OrdersModel::where('status',1)->count();
                $subScribedUsersCount = SubscriptionsModel::where('status',1)->count();
                return view('admin.dashboard',compact('userCount','productCount','categoryCount','brandCount','orderCount','vipProductCount','subScribedUsersCount')); 
            }else{
                return redirect('/');
            }
        }else{
            return redirect('/');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect('admin/login');
    }
}
