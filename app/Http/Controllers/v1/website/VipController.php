<?php

namespace App\Http\Controllers\v1\website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ProductsModel;
use App\Models\BrandsModel;
use App\Models\CategoriesModel;
use App\Models\ProductSizeTypes;
use App\Models\SubscriptionPlan;
use App\Models\VipSaleCounter;

use Illuminate\Support\Facades\Input;

class VipController extends Controller
{
	public function vipHome()
	{

		$vipData = array();
		$vipData['relatedProducts'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1'])->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();

		return view('website.vip.vip-home', $vipData);
	}

	public function vipnewHome()
	{

		$vipData = array();

		//Get orders for VIP
		$vipData = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();

		$vipCountertime = VipSaleCounter::where(['status' => '1'])->get()->first()->toArray();

		$plans = SubscriptionPlan::where(['status' => '1'])->orderby('id', 'desc')->get()->toArray();

		//fetch the products for the VIP page
		//echo "<pre>"; print_r($vipData['relatedProducts']); echo "</pre>";

		return view('website.vip.vipnew-home', compact('vipData', 'plans','vipCountertime'));
	}


	/*This method display the VIP STORE page with product listing
	*@Params
	*@ Return
	*/
	public function vipStore()
	{
		$vipStore = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();

		$vipCountertime = VipSaleCounter::where(['status' => '1'])->get()->first()->toArray();
		//dump($vipCountertime['start_date']);
	
		return view('website.vip.vip-store', compact('vipStore','vipCountertime'));
	}


	/*This method display the sale products for VIP Members
	*@Params user_id
	 * @Return VIP Products
	 * */
	public function vipSale()
	{
		$vipSale = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->paginate(5);
		$vipCountertime = VipSaleCounter::where(['status' => '1'])->get()->first()->toArray();
		//print_r($vipSale);
		//exit();
		//echo "<pre>"; print_r($vipStore); echo "</pre>";

		return view('website.vip.vip-sale',compact('vipSale','vipCountertime'));
	}

	//vip load products
	public function laodproduct(Request $request)
	{
		// $vipSale = ProductsModel::with([
		// 	'productCategory',
		// 	'productBrandType',
		// 	'productBrand',
		// 	'productSizeTypes'
		// ])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->skip(5)->limit(5)->get()->toArray();
		// if($vipSale){
		// 	return $vipSale;
		// }else{
		// 	return "No Product found";
		// }
        
		if ($request->has('page')) {
			//$articles=Article::paginate(5);
			$vipSale = ProductsModel::with([
				'productCategory',
				'productBrandType',
				'productBrand',
				'productSizeTypes'
			])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->paginate(5);
		}
		$returnHTML = view('website.vip.vip-more-product', compact('vipSale'))->render();
        return response()->json(['html'=>$returnHTML,'page'=>$vipSale->currentPage(),'hasMorePages'=>$vipSale->hasMorePages()]);
    
	}

	/*fetch all products which are availabe for VIP Members	*
	 * VIP_STATUS = 1
	 * Products of All Categories with status = 1
	 * Products of All Brands with status = 1
	 * */
	public function getVipProductListing()
	{
		$vipProductsData = array();
		$brand_ids = [];
		$category_ids = [];
		$brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
		$category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		//fetch only VIP Products whose vip_status=1
		$vipProductsData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )
			->whereIn('category_id',$category_ids)
			->where(['status' => '1'])
			->where(['vip_status' => '1'])
			->orderBy('updated_at', 'DESC')
			->paginate();


		$productsData = $vipProductsData['paginate']->toArray();

		$vipProductsData['allProducts'] = $productsData['data'];

		$vipProductsData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

		$vipProductsData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
		//Set category ID and use it on frontend
		$vipProductsData['category_id'] = 1;
		//dump($productsData['data']);
		// exit();

		return view('website.vip.vip-products', $vipProductsData);
	}

	/*Filter the VIP Products
	 *@Params Request
	 *@Return res
	 * vip_status = 1 from `products table`
	 * */
	public function vipFilterProducts(Request $request)
	{

		$requestedData = $request->all();
		$brandFilter = array();
		$sizeTypeFilter = array();
		$sizeID = '';
		$filteredBrandsList = '';


		if (isset($requestedData['filters'])) {

			$filters = $requestedData['filters'];
			$category_id = base64_decode($requestedData['category_id']);
			foreach ($filters as $key => $value) {
				if (is_numeric($value)) {
					$brandFilter[] = $value;
				} else {
					$sizeTypeFilter[] = $value;
				}
			}

			$shoesData['paginate'] = ProductsModel::with([
				'productCategory',
				'productBrandType',
				'productBrand',
				'productSizeTypes'
			])->where('status', 1)
				->where('vip_status', 1)
				->where('category_id', $category_id)
				->orderBy('updated_at', 'DESC');


			if (!empty($sizeTypeFilter) && !empty($brandFilter)) {
				$shoesData['paginate'] = $shoesData['paginate']->whereIn('brand_id',
					$brandFilter)->whereIn('size_type_id', $sizeTypeFilter);
			}
			if (!empty($sizeTypeFilter) && empty($brandFilter)) {
				$shoesData['paginate'] = $shoesData['paginate']->whereIn('size_type_id', $sizeTypeFilter);
			}
			if (empty($sizeTypeFilter) && !empty($brandFilter)) {
				$shoesData['paginate'] = $shoesData['paginate']->whereIn('brand_id', $brandFilter);
			}

			$shoesData['paginate'] = $shoesData['paginate']->paginate();

			$productsData = $shoesData['paginate']->toArray();

			$shoesData['allProducts'] = $productsData['data'];
			$shoesData['filters'] = $shoesData['paginate']->appends(Input::except('page'));


			if ($request->isMethod('get')) {

				$shoesData['allBrands'] = BrandsModel::all()->toArray();
				$shoesData['allSizeTypes'] = ProductSizeTypes::all()->toArray();

				return view('website.all-shoes', $shoesData);
			} else {
				return view('website.partial.filtered-products', $shoesData);
			}

		} else {

			return 'reset';
			$shoesData['paginate'] = ProductsModel::with([
				'productCategory',
				'productBrandType',
				'productBrand',
				'productSizeTypes'
			])->paginate();

			$productsData = $shoesData['paginate']->toArray();
			$shoesData['allProducts'] = $productsData['data'];

			return view('website.partial.filtered-products', $shoesData);
		}


	}
}
