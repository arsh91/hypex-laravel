<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdersModel;
use App\Models\OrdersShippedModel;
use App\Models\UsersBillingAddressModel;
use App\Models\UsersShippingAddressModel;
use App\Http\Requests\OrderAllDetailRequest;
use DB;
use Datatables;

class ShippingManagementController extends Controller
{
   

    public function list(){
        $shipping = OrdersShippedModel::where(['status' => 1])->orderby('id', 'desc')->get()->toArray();
        //print_r($shipping);
        //exit();
        return view('admin.shipping.shipping-list', ['shipping' => $shipping]);
    }
 
    
    public function view($id)
    {
        $shipping = OrdersShippedModel::where('id', $id)->first()->toArray();
        return view('admin.shipping.each-shipping-detail', ['shipping' => $shipping]);
    }

    public function deleteorder($order_id, $status){
        $order = OrdersModel::find($order_id);
        $order->status = $status;
        if($order->save()){            
            return redirect('/admin/order-list')->with('success', "Order has been deleted successfully.");
        }else{
            return redirect('/admin/order-list')->with('error', "Error in deleting. Please try again later");
        }
    }

   
}
