<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\BrandsModel;
use App\APIModels\CategoriesModel;

class BrandsModel extends Model
{
    protected $table ='brands';
	//protected $appends = ['brand_types_list_with_comma'];
	public function brandTypes(){
		return $this->hasMany('App\APIModels\BrandTypesModel','brand_id','id');
	}
	public function categoryBrands(){
		return $this->belongsToMany('App\APIModels\CategoriesModel','category_brands','brand_id','category_id');
	}

	/*public function getBrandTypesListWithCommaAttribute(){
		return implode(', ',$this->brandTypes()->pluck('brand_type_name')->toArray());
	}*/
	
}
