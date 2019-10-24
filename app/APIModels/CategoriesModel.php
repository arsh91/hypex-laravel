<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
	
	public function categoryBrands(){
		return $this->belongsToMany('App\APIModels\BrandsModel','category_brands','category_id','brand_id')->withPivot('status');
	}
	
	public function getCategoryBrand(){
		 return $this->categoryBrands();
	}
	
}
