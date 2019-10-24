<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class ProductsSeller extends Model
{
    protected $table = 'products_seller';
	protected $fillable = ['user_id','product_id','size_id','actual_price','ask_price','billing_address_id','shiping_address_id','sell_expiry_date','status'];
	 
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
	
	public function sellOrder(){
		return $this->hasOne('App\APIModels\OrdersModel','seller_id','id');
	}
	public function sellerPayoutModel(){
		return $this->hasOne('App\APIModels\SellerPayoutModel','product_seller_id','id');
	}
}
