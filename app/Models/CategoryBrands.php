<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBrands extends Model
{
    protected $table = 'category_brands';
	protected $fillable = array('brand_id', 'category_id', 'status','created_at','created_at');
	public function categoryBrands(){
		return $this->belongsToMany('App\Models\BrandsModel','brand_id','id');
	}
}
