<?php

namespace App\Http\Controllers\v1\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrdersModel;
use App\Models\ProductsBidder;
use App\Models\ProductSellerModel;
use App\Http\Requests\OrderAllDetailRequest;
use DB;
use Datatables;

class StatsManagementController extends Controller
{
   

    public function list(){
        $orders = OrdersModel::with(['users','seller','product','procategory','brand'])->where(['status' => 1])->orderby('id', 'desc')->get()->toArray();
        return view('admin.order.order-list', ['orders' => $orders]);
    }

    public function highestbid(){

        $highestbid = ProductsBidder::with('product')->get()->groupBy('product_id')->toArray();

        if(!empty($highestbid))
        {
            $highestbid = ProductsBidder::with('product')->get()->groupBy('product_id')->max()->toArray();
        }else{
            $highestbid = array();
        }

        $lowestbid = ProductsBidder::with('product')->get()->groupBy('product_id')->toArray();

        if(!empty($lowestbid))
        {
            $lowestbid = ProductsBidder::with('product')->get()->groupBy('product_id')->min()->toArray();
        }else{
            $lowestbid = array();
        }

        $highestordered = OrdersModel::with('product')->get()->groupBy('product_id')->toArray();
        if(!empty($highestordered))
        {
            $highestordered = OrdersModel::with('product')->get()->groupBy('product_id')->max()->toArray();
        }else{
            $highestordered = array();
        }

        $lowestordered = OrdersModel::with('product')->get()->groupBy('product_id')->toArray();
        if(!empty($lowestordered))
        {
            $lowestordered = OrdersModel::with('product')->get()->groupBy('product_id')->min()->toArray();
        }else{
            $lowestordered = array();
        }

        $highestsell = ProductSellerModel::with('product')->get()->groupBy('product_id')->toArray();
        if(!empty($highestsell))
        {
            $highestsell = ProductSellerModel::with('product')->get()->groupBy('product_id')->max()->toArray();
        }else{
            $highestsell = array();
        }

        $lowestsell = ProductSellerModel::with('product')->get()->groupBy('product_id')->toArray();
        if(!empty($lowestsell))
        {
            $lowestsell = ProductSellerModel::with('product')->get()->groupBy('product_id')->min()->toArray();
        }else{
            $lowestsell = array();
        }

        return view('admin.stats.highest-bids', ['highestbid' => $highestbid , 'lowestbid' => $lowestbid ,'highestordered' => $highestordered , 'lowestordered' => $lowestordered ,'highestsell' => $highestsell , 'lowestsell' => $lowestsell]);
    }


    
   
}
