<?php

namespace App\Http\Controllers\v1\website;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\BrandsModel;
use App\Models\BrandTypesModel;
use App\Models\CategoriesModel;

class BrandsController extends Controller
{
	function brandData($id= null){
		$brand = BrandsModel::with(['brandTypes'])->find(1);
		echo "<pre>"; print_r($brand->toArray()); die;
	}
	
	function category(){
		$category = CategoriesModel::get();
		echo "<pre>"; print_r($category->toArray()); die;	
	}
	
}
