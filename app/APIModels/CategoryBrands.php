<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class CategoryBrands extends Model
{
    protected $table = 'category_brands';
	
	public function categoryBrands(){
		return $this->belongsToMany('App\Models\BrandsModel','brand_id','id');
	}
}
