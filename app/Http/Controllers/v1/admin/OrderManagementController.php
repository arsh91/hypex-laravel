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

class OrderManagementController extends Controller
{
   

    public function list(){
        $orders = OrdersModel::with(['users','seller','product','procategory','brand'])->whereIn('status',[1, 2, 3] )->where('vip_order','0')->orderby('id', 'desc')->get()->toArray();
        return view('admin.order.order-list', ['orders' => $orders]);
    }
    
    public function vipOrderList(){
        $orders = OrdersModel::with(['users','seller','product','procategory','brand'])->whereIn('status',[1, 2, 3])->where('vip_order','1')->orderby('id', 'desc')->get()->toArray();
        return view('admin.order.vip-order-list', ['orders' => $orders]);
    }

    public function rejectedlist(){
        $orders = OrdersModel::with(['users','seller','product','procategory','brand'])->where(['status' => 0] )->orderby('id', 'desc')->get()->toArray();
        return view('admin.order.order-rejected', ['orders' => $orders]);
    }

    public function historylist(){
        $orders = OrdersModel::with(['users','seller','product','procategory','brand'])->where(['status' => 3] )->orderby('id', 'desc')->get()->toArray();
        return view('admin.order.order-history', ['orders' => $orders]);
    }
 
    public function view($id)
    {
        $orders = OrdersModel::with(['users','seller','product','procategory','brand','shipping','billing'])->where('id', $id)->first()->toArray();
        //print_r($orders);
        //exit();
        return view('admin.order.each-order-detail', ['orders' => $orders]);
    }

    public function rejectOrder($order_id, $status){
        $order = OrdersModel::find($order_id);
        $order->status = $status;
        if($order->save()){            
            return redirect('/admin/order-list')->with('success', "Order has been rejected.");
        }else{
            return redirect('/admin/order-list')->with('error', "Error in rejecting the order. Please try again later");
        }
    }
    
    public function labeldata($order_id){
        $order = OrdersModel::find($order_id);
        //echo $order->id;
        $base64 = "";
        $decoded = base64_decode($base64);
        $file = 'label.pdf';
        file_put_contents($file, $decoded);
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
        return redirect('/admin/order-list')->with('error', "Error in deleting. Please try again later");
        
    }

    
    public function getoderdata($order_refrencenumber, $orderid) {

        $url = 'https://ssapi.shipstation.com/orders?orderNumber=hypex_'.$order_refrencenumber;
        $ch = curl_init($url);
        $options = array(
                CURLOPT_RETURNTRANSFER => true,         // return web page
                CURLOPT_HEADER         => false,        // don't return headers
                CURLOPT_FOLLOWLOCATION => false,         // follow redirects
               // CURLOPT_ENCODING       => "utf-8",           // handle all encodings
                CURLOPT_AUTOREFERER    => true,         // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 20,          // timeout on connect
                CURLOPT_TIMEOUT        => 20,          // timeout on response
                CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
                CURLOPT_SSL_VERIFYPEER => false,        //
                CURLOPT_VERBOSE        => 1,
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM="
                )

        );
        curl_setopt_array($ch,$options);
        $data = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        $orderdata = json_decode($data);
        if(!isset($orderdata->orders['0'])){
            
            return redirect('/admin/order-list')->with('success', "Please try after 24 hours, ShipStation is reviewing this order.");
        }
        $ordershipstation_id = $orderdata->orders['0']->orderId;
        if($ordershipstation_id)
        {
            $url = 'https://ssapi.shipstation.com/orders/createlabelfororder';
            $request = $data = json_encode(array(
                "orderId" => $ordershipstation_id,
                "carrierCode" => "canada_post",
                "serviceCode" => "xpresspost",
                "packageCode" => "package",
                "shipDate" => date("Y-m-d"),
            ));
            $ch = curl_init($url);
            $options = array(
                    CURLOPT_RETURNTRANSFER => true,         // return web page
                    CURLOPT_HEADER         => false,        // don't return headers
                    CURLOPT_FOLLOWLOCATION => false,         // follow redirects
                // CURLOPT_ENCODING       => "utf-8",           // handle all encodings
                    CURLOPT_AUTOREFERER    => true,         // set referer on redirect
                    CURLOPT_CONNECTTIMEOUT => 20,          // timeout on connect
                    CURLOPT_TIMEOUT        => 20,          // timeout on response
                    CURLOPT_POST            => 1,            // i am sending post data
                    CURLOPT_POSTFIELDS     => $request,  
                    CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
                    CURLOPT_SSL_VERIFYPEER => false,        //
                    CURLOPT_VERBOSE        => 1,
                    CURLOPT_HTTPHEADER     => array(
                        "Authorization: Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM=",
                        "Content-Type: application/json"
                    )

            );
            curl_setopt_array($ch,$options);
            $data2 = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);
            $orderlabeldata = json_decode($data2);
            //print_r($orderlabeldata->labelData);

            $base64 = $orderlabeldata->labelData;
            $decoded = base64_decode($base64);
            // $file = 'kuldeep.pdf';
            $file = time().$orderlabeldata->shipmentId.$orderlabeldata->shipTo->name.'.pdf';
            file_put_contents(public_path('/v1/admin/images/labelspdf/').$file, $decoded);
            if (file_exists($file)) {

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public/v1/admin/images/labelspdf');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                //$upload = $file->move(public_path('/v1/admin/images/labelspdf/')); 
            }

            if($orderlabeldata->labelData)
            {
                $insert = OrdersShippedModel::create([
                    'order_id' => $orderid,
                    'shipsation_order_id' => $ordershipstation_id,
                    'shipment_id' => $orderlabeldata->shipmentId,
                    'ship_date' => $orderlabeldata->shipDate,
                    'shipment_cost' => $orderlabeldata->shipmentCost,
                    'tracking_number' => $orderlabeldata->trackingNumber,
                    'carrier_code' => $orderlabeldata->carrierCode,
                    'service_code' => $orderlabeldata->serviceCode,
                    'package_code' => $orderlabeldata->packageCode,
                    'label_data' => $file,
                    'status' => 1
                ]);
                if($insert){
                    $order = OrdersModel::find($orderid);
                    $order->status = 2;
                    $order->save();
                }
            }
        }
        if($insert){            
            return redirect('/admin/order-list')->with('success', "Order shippped successfully.");
        }else{
            return redirect('/admin/order-list')->with('error', "Error in shippped. Please try again later");
        }
      
    }

   
}
