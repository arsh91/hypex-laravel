<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BrandsModel;


class BrandsModel extends Model
{
    protected $table ='brands';
	protected $appends = ['brand_types_list_with_comma'];
	public function brandTypes(){
		return $this->hasMany('App\Models\BrandTypesModel','brand_id','id');
	}

	public function getBrandTypesListWithCommaAttribute(){
		return implode(', ',$this->brandTypes()->pluck('brand_type_name')->toArray());
	}
	
}
