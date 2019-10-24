<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BrandsModel;

class BrandTypesModel extends Model
{
    protected $table = 'brand_types';
	
	public function brandDetail(){
		return $this->belongsTo('App\Models\BrandsModel','brand_id','id');
	}
	
	
}
