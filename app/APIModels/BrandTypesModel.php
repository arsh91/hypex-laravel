<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\BrandsModel;

class BrandTypesModel extends Model
{
    protected $table = 'brand_types';
	
	public function brandDetail(){
		return $this->belongsTo('App\APIModels\BrandsModel','brand_id','id');
	}
	
	
}
