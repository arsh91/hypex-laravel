<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductsBidder;
use App\Models\ProductSellerModel;
use App\Models\ProductsModel;
use App\Models\OrdersModel;
use App\Http\Requests\UserAllDetailRequest;

use Datatables;
use App\User;

class UserManagementController extends Controller
{
    public function userList(){
        return view('admin/user/user-list');
    }

    public function index()
    {
        //['userbids','usersells']
        $users = User::with(['userbids' => function ($query) {
            $query->where('status', '=', '1');
        } , 'usersells' => function ($query) {
            $query->where('status', '=', '1');
        }])->where(['is_admin' => 0])->orderBy('id', 'desc')->get()->toArray();
       // print_r($users);
       // exit();
        return view('admin.user.user-list', ['users' => $users]);
    }

    public function userListPaginate(){
        $get_user_list = User::where(['is_admin' => 0])->orderBy('updated_at','DESC')->get();
        return Datatables::of($get_user_list)
                    ->setRowClass('clickable-row')
                    ->addIndexColumn()
                    ->editColumn('phone', function(User $UserList) {
                        return !empty($UserList->phone) ? $UserList->phone: 'N/A';
                    })
                    ->editColumn('state', function(User $UserList) {
                        return !empty($UserList->state) ? $UserList->state: 'N/A';
                    })
                    ->editColumn('country', function(User $UserList) {
                        return !empty($UserList->country) ? $UserList->country: 'N/A';
                    })
                    ->addColumn('status_custom', function(User $UserList) {
                        if($UserList->status == 1){
                            $return_data = '<label class="badge badge-gradient-success">Active</label>';
                        }else{
                            $return_data = '<label class="badge badge badge-danger">Block</label>';
                        }
                        return $return_data;
                    })
                    ->rawColumns(['status_custom'])
                    ->make(true);
    }

    public function eachUserDetail($id){
        $get_user_detail = User::findorFail($id);
        return view('admin/user/each-user-detail',compact('get_user_detail'));
    }

    public function eachUserEdit(UserAllDetailRequest $request, $id){
        if($request->isMethod('post')){
            $validated = $request->validated();
            $update_account = User::where(['is_admin' => 0, 'id' => $id])->firstOrFail();
            $update_account->first_name = $request->first_name;
            $update_account->last_name = $request->last_name;
            $update_account->user_name = $request->user_name;
            $update_account->email = $request->email;
            $update_account->phone = !empty($request->phonecity) ? $request->city : '';
            $update_account->city =!empty($request->city) ? $request->city : '';
            $update_account->state = !empty($request->state) ? $request->state : '';
            $update_account->country = !empty($request->country) ? $request->country : '';
            $update_account->postal_code = !empty($request->postal_code) ? $request->postal_code : '';
            $update_account->status = ($request->status == 2) ? 0 : 1;
            $update_account->updated_at = Date('Y-m-d H:i:s');
            $update_account->save();
            return redirect('admin/user-list')->with('success', 'User profile uploaded successfully!');
        }else{
            $get_user_detail = User::where(['is_admin' => 0, 'id' => $id])->firstOrFail();
            return view('admin/user/each-user-edit',compact('get_user_detail'));
        }
    }

    public function changeStatus($user_id, $status){
        $user = User::find($user_id);
        $user->status = $status;
        if($user->save()){ 
            return redirect('/admin/user-list')->with('success', "Status has been changed successfully.");
        }else{
            return redirect('/admin/user-list')->with('error', "Error in changing status. Please try again later");
        }
    }

    public function bidsProductList($user_id){
        $bidds = ProductsBidder::with(['product','size'])->where(['user_id' => $user_id])->orderby('id', 'desc')->get()->toArray();
        return view('admin/user/bids-products',compact('bidds'));
    }

    public function buyinghistory($user_id){
        $buying_history = OrdersModel::with(['product','procategory','brand'])->where(['bought_by' => $user_id])->orderby('id', 'desc')->get()->toArray();
        return view('admin/user/buy-history',compact('buying_history'));
    }

    public function sellProductLists($user_id){
        $sells = ProductSellerModel::with(['product','size'])->where([ 'user_id' => $user_id])->orderby('id', 'desc')->get()->toArray();
        return view('admin/user/sell-products',compact('sells'));
    }

    public function sellinghistory($user_id){
        $selling_history = OrdersModel::with(['ordershipped','product','procategory','brand'])->where(['sold_by' => $user_id])->orderby('id', 'desc')->get()->toArray(); 
        return view('admin/user/sell-history',compact('selling_history'));
    }


}
