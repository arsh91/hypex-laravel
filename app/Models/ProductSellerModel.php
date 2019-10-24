<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSellerModel extends Model
{
    protected $table = 'products_seller';
    protected $fillable = ['user_id','product_id','size_id','actual_price','shipping_price','ask_price','total_price','commission_price','processing_fee','billing_address_id','shiping_address_id','sell_expiry_date','status'];
    
    public function product(){
		return $this->belongsTo('App\Models\ProductsModel','product_id');
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

    public function sellerdata(){
      return $this->belongsTo('App\Models\OrdersModel','id','seller_id');
    }
    public function ordershipped(){
      return $this->belongsTo('App\Models\OrdersShippedModel','buyyerdata["id"]','order_id');
    }
}
