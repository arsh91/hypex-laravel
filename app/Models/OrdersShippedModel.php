<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersShippedModel extends Model
{
    protected $table = 'order_shipped_details';
    protected $fillable = ['order_id','shipsation_order_id','shipment_id','ship_date','shipment_cost','tracking_number','carrier_code','service_code','package_code','label_data','status'];
    
    

}
