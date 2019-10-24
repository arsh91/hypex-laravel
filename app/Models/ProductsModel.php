<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductsModel extends Model
{
    protected $table = 'products';
	protected $perPage = 28;
	protected $appends = ['product_image_link','release_date_format','other_product_image_link'];
	
	public function productSizes(){
		return $this->hasMany('App\Models\ProductsSizes','product_id','id');
	}
	
	
	public function productSizeTypes(){
		
		return $this->hasOne('App\Models\ProductSizeTypes','id','size_type_id')->where(['status' => '1']);
	}
	
	public function productBrand(){
		
		return $this->belongsTo('App\Models\BrandsModel','brand_id','id')->where(['status' => '1']);
	}
	
	public function productBrandType(){
		
		return $this->belongsTo('App\Models\BrandTypesModel','brand_type_id','id')->where(['status' => '1']);
	}
	
	public function productCategory(){
		
		return $this->belongsTo('App\Models\CategoriesModel','category_id','id')->where(['status' => '1']);
	}

	public function getProductImageLinkAttribute(){
		return explode(',',$this->attributes['product_images']);
	}

	public function getOtherProductImageLinkAttribute(){
		return explode(',',$this->attributes['other_product_images']);
	}

	public function getReleaseDateFormatAttribute(){
		return Carbon::createFromFormat('Y-m-d H:i:s',$this->attributes['release_date'])->toDateString(); 
	}
	
	public function productsBidder(){
		return $this->hasMany('App\Models\ProductsBidder','product_id','id')->where(['status' => '1']);
	}
	
	public function productsSeller(){
		return $this->hasMany('App\Models\ProductsSeller','product_id','id')->where(['status' => '1']);
	}


}
