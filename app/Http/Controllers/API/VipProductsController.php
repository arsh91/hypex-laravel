<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\APIModels\ProductsModel;
use App\APIModels\BrandsModel;
use App\APIModels\BrandTypesModel;
use App\APIModels\OrdersModel;
use App\APIModels\ProductSizeTypes;
use App\APIModels\SizesModel;

use App\APIModels\CategoriesModel;
use App\APIModels\HomeSliderModel;
use App\APIModels\BlogsModel;
use App\APIModels\StreetwearSizesModel; 
use App\APIModels\ProductsSizes;
use App\APIModels\YearsModel;
use App\APIModels\PricesModel;
use App\APIModels\SeasonsModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use Carbon\Carbon;

class VipProductsController extends Controller
{
	
	/** 
     * Featured product api 
     * 
     * @return Featured product list
	*/
	public function getVIPProducts(Request $request){
		$offset = $request->offset;
		$limit = $request->limit;
		$productCount = array();
		$featuredProductsList = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['trending', '=', '1'],['status', '=', '1']]);
		
		$featuredCount = $featuredProductsList->get()->toArray();
		$featuredProducts = $featuredProductsList->offset($offset)->limit($limit)->get()->toArray();
		$featured = array();
		if(count($featuredProducts) > 0){
			foreach($featuredProducts as $singleProduct){
			$file='';
				if(!empty($singleProduct['product_images'])) {
					$file = $singleProduct['product_images'];
					$prodImages = explode(',',$file);
					$singleProduct['product_images'] = url('/').'/'.current($prodImages);
					$singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
					$featured[] = $singleProduct;
				}
			}
			$productCount = count($featuredCount);
			$featuredProduct['totalCount'] = $productCount;
			$featuredProduct['results'] = $featured;
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$featuredProduct]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No products available.', 'data'=>(object)[]]);
		}	
	}
	
	/*** common functions **/
	public function getBrandNameById($id){
		$brands = BrandsModel::where("id", $id)->get()->first();
		return $brands['brand_name'];
	}
	
}
