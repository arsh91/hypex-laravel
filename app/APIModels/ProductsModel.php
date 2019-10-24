<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductsModel extends Model
{
    protected $table = 'products';
	//protected $appends = ['product_image_link','release_date_format','other_product_image_link'];
	public function productSizes(){
		
		return $this->hasMany('App\APIModels\ProductsSizes','product_id','id');
	}
	
	
	public function productSizeTypes(){
		
		return $this->hasOne('App\APIModels\ProductSizeTypes','id','size_type_id');
	}
	
	public function productBrand(){
		
		return $this->belongsTo('App\APIModels\BrandsModel','brand_id','id');
	}
	
	public function productBrandType(){
		
		return $this->belongsTo('App\APIModels\BrandTypesModel','brand_type_id','id');
	}
	
	public function productCategory(){
		
		return $this->belongsTo('App\APIModels\CategoriesModel','category_id','id');
	}
	public function productOrders(){
		
		return $this->hasMany('App\APIModels\OrdersModel','product_id','id');
	}
	
	public function productsBidder(){
		return $this->hasMany('App\Models\ProductsBidder','product_id','id')->where('status',1);
	}
	
	public function productsSeller(){
		return $this->hasMany('App\Models\ProductsSeller','product_id','id')->where('status',1);
	}
	
}
