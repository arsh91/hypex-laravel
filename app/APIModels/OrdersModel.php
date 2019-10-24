<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    protected $table = 'orders';
	protected $fillable = ['order_ref_number','product_id','product_size_id','user_id','seller_id','bidder_id','price','total_price','shipping_price','payment_data','shiping_address_id','billing_address_id','payout_email','status'];

	public function productBrand(){
		
		return $this->belongsTo('App\APIModels\BrandsModel','brand_id','id');
	}
	public function users(){
		return $this->belongsTo('App\User','user_id','id');
    }
    public function seller(){
		return $this->belongsTo('App\User','seller_id','id');
    }
    public function product(){
		return $this->belongsTo('App\Models\ProductsModel','product_id','id');
    }
    public function procategory(){
		return $this->belongsTo('App\Models\CategoriesModel','category_id','id');
    }
    public function brand(){
		return $this->belongsTo('App\Models\BrandsModel','category_id','id');
    }
    public function shipping(){
		return $this->belongsTo('App\Models\UsersShippingAddressModel','user_id','user_id');
    }
    public function billing(){
		return $this->belongsTo('App\Models\UsersBillingAddressModel','user_id','user_id');
    }
    public function size(){
		return $this->belongsTo('App\Models\ProductsSizes','product_size_id','id');
    }
    public function ordershipped(){
      return $this->belongsTo('App\Models\OrdersShippedModel','id','order_id');
    }
}
