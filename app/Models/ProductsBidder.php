<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsBidder extends Model
{
    protected $table = 'products_bidder';

	protected $fillable = ['user_id','product_id','size_id','actual_price','shipping_price','bid_price','total_price','commission_price','processing_fee','billing_address_id','shiping_address_id','bid_expiry_date','currency_code','status'];


    public function product(){
		return $this->belongsTo('App\Models\ProductsModel','product_id','id');
    }
    
    public function users(){
      return $this->belongsTo('App\User','user_id','id');
      }

    public function size(){
		return $this->belongsTo('App\Models\ProductsSizes','size_id','id');
    }
    
    public function billingAddress(){
		return $this->belongsTo('App\Models\UsersBillingAddressModel','billing_address_id','id');
    }
    
    public function shippingAddress(){
		return $this->belongsTo('App\Models\UsersShippingAddressModel','shiping_address_id','id');
    }

    public function buyyerdata(){
      return $this->belongsTo('App\Models\OrdersModel','id','bidder_id');
    }

    

}
