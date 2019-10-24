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
use App\APIModels\CategoryBrands;
use App\APIModels\HomeSliderModel; 
use App\APIModels\BlogsModel;
use App\APIModels\StreetwearSizesModel;
use App\APIModels\ProductsSizes;
use App\APIModels\YearsModel;
use App\APIModels\PricesModel;
use App\APIModels\SeasonsModel;
use App\Models\ProductsBidder;
use App\APIModels\ProductsSeller;

use App\Helpers\WebHelper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use Carbon\Carbon;

class ProductsController extends Controller
{
	/** 
     * Home api 
     * 
     * @return home page arrays
     */ 
    public function getHomeDetails(){
		
		$vipSale['days'] = date('d');
		$vipSale['hour'] = date('H');
		$vipSale['minute'] = date('i');
		$vipSale['second'] = date('s');
		//$homeData['RecommendedProducts'] = ProductsModel::with(['productCategory','productBrandType','productBrand','productSizeTypes'])->get()->toArray();
		$homeData['homeSlider'] = $this->homeSlider();
		$homeData['RecommendedProducts'] = $this->RecommendedProducts();
		$homeData['featuredProducts']  = $this->getFeaturedProducts();
		$homeData['bargainProducts'] = $this->getBargainProducts();
		$homeData['blogs'] = $this->getBlogs();
		$homeData['vipTimer'] = $vipSale;
		$homeData['backDoorSale'] = $vipSale;
		$homeData['brands'] = $this->getBrand('home');
		$homeData['categories']  = $this->getCategories('home');
		
		if(!empty($homeData)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$homeData]);
		} else {
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No products available.', 'data'=>$homeData]);
		}
		
	}
	function homeSlider(){
		$homeSliders = HomeSliderModel::get()->toArray();
		foreach($homeSliders as $sliders){
			if(!empty($sliders['image_url'])) {
				$singleImage['id'] = $sliders['id'];
				$singleImage['image_url'] = url('/').'/'.$sliders['image_url'];
				$slider[] = $singleImage;
			}
		}
		return $slider;
		
	}
	
	function getFeaturedProducts(){
		
		$featuredProducts = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['trending',1],['status', '=', '1']])->inRandomOrder()->take(5)->get()->toArray();
		$featured = array();
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
		return $featured;
		
	}
	
	function RecommendedProducts(){
		$RecommendedProducts = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where('status',1)->orderBy('created_at', 'asc')->take(5)->get()->toArray();
		$RecommendedProduct = array();
		foreach($RecommendedProducts as $singleProduct){
		$file='';
			if(!empty($singleProduct['product_images'])) {
				$file = $singleProduct['product_images'];
				$prodImages = explode(',',$file);
				$singleProduct['product_images'] = url('/').'/'.current($prodImages);
				$singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
				$RecommendedProduct[] = $singleProduct;
			}
		}
		return $RecommendedProduct;
		
	}
	
	function getBrand($home = null){
		$brands = BrandsModel::where('status',1)->get()->toArray();
		$allBrands = array();
		foreach($brands as $brand){
			if(!empty($brand['brand_image'])){
				$brand['brand_image'] = url('/').'/'.$brand['brand_image'];
			}else{
				$brand['brand_image'] = url('/').'/public/v1/website/img/brands/Nike.png';
			}
			$allBrands[] = $brand;
		}
		if($home == 'home'){ return $allBrands; }
		else { return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Brands retrieved succesfully.', 'data'=>$allBrands]); }
	}
	
	function getCategories($home = null){
		$categories = CategoriesModel::take(6)->get()->toArray();
		$allcategories = array();
		foreach($categories as $category){
			if(!empty($category['category_image'])){
				$category['category_image'] = url('/').'/'.$category['category_image'];
			}else{
				$category['category_image'] = url('/').'/public/v1/website/img/category/Shoes.png';
			}
			$allcategories[] = $category;
		}
		if($home == 'home'){ return $allcategories; }
		else { return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Categories retrieved succesfully.', 'data'=>$allcategories]); }
		
	}
	
	
	
	function getBargainProducts(){
		//this will be done after admin functionality
		
		$bargainProducts = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['trending',0],['status', '=', '1']])->inRandomOrder()->take(3)->get()->toArray();
		$featured = array();
		foreach($bargainProducts as $singleProduct){
		$file='';
			if(!empty($singleProduct['product_images'])) {
				$file = $singleProduct['product_images'];
				$prodImages = explode(',',$file);
				$singleProduct['product_images'] = url('/').'/'.current($prodImages);
				$singleProduct['bargain_price'] = 'US$90';
				$singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
				$featured[] = $singleProduct;
			}
		}
		return $featured;
		
	}
	function getBlogs(){
		$blogs = BlogsModel::get()->toArray();
		$getBlogs = array();
		foreach($blogs as $blog){
		$file='';
			if(!empty($blog['image_url'])) {
				$file = $blog['image_url'];
				$prodImages = explode(',',$file);
				$blog['image_url'] = url('/').'/'.current($prodImages);
				$getBlogs[] = $blog;
			}
		}
		return $getBlogs;
		
	}
	
	/** 
     * Featured product api 
     * 
     * @return Featured product list
	*/
	public function getFeaturedProductsList(Request $request){
		$offset = $request->offset;
		$limit = $request->limit;
		$search = $request->search;
		$productCount = array();
		if(!empty($search)){
			$featuredProductsList = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['trending', '=', '1'],['status', '=', '1'],['product_name', 'like', '%'.$search.'%']]);
		}else{
			$featuredProductsList = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['trending', '=', '1'],['status', '=', '1']]);
		}
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
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No Products Available', 'data'=>(object)[]]);
		}	
	}
	/** 
     * Product detail API
     * 
     * @return product detail with related products
	*/
	public function getProductDetail(Request $request){
		$productDetails = array();
		$productID = $request->product_id;
		if (ProductsModel::where('id', '=', $productID)->exists()) {
			
			$productDetail = ProductsModel::with(['productCategory','productBrandType',
			'productBrand','productSizeTypes','productSizes','productsBidder','productsSeller','productOrders'])->findOrFail($productID)->toArray();
			//$productDetail = $productDetails['productDetails'];
			$bidData = $productDetail['products_bidder'];
			$sellData = $productDetail['products_seller'];
			$prodSizeData = $productDetail['product_sizes'];
			$prodOrders = $productDetail['product_orders'];
			
		//print_r($bidData); die;
			
		// getting All sizes and its primaryID
		foreach($prodSizeData as $key=>$value){
			$sizeArray[$value['id']] = $value['size'];
		}
		
		// Merging Primary
		if(!empty($bidData)){
			foreach($bidData as $key=>$value){
				$size = $sizeArray[$value['size_id']];
				$finalBidData[$size][] = $value['bid_price'];
				//$finalBid[$size]['seller_id'] = $value['id'];
				//$finalBid[$size]['size'] = $size;
				//$finalBid[$size]['price'] = $value['bid_price'];
				//$finalBidData[] = $finalBid;
				
			}
		}else{
			$finalBidData = array();
		}
		
		// Merging Primary IDs for Sell
		
		if(!empty($sellData)){
			foreach($sellData as $key=>$value){
				$size = $sizeArray[$value['size_id']];
				$finalSellData[$size][] = $value['ask_price'];
				
			}
		}else{
			$finalSellData = array();
		}
			
		// fetching Max Bid from all the Bid Data
		if(!empty($finalBidData)){
			foreach($finalBidData as $key=>$value){
				$maxBidsData[$key] = max($value);
			}
		}else{
			$maxBidsData = array();
		}
		
		
		if(!empty($finalSellData)){
			foreach($finalSellData as $key=>$value){
				$minSellData[$key] = min($value);
			}
		}else{
			$minSellData = array();
		}
			
		if(!empty($maxBidsData)){
			$minBidSize = current(array_keys($maxBidsData, min($maxBidsData)));
		}else{
			$minBidSize = 0;
		}
			
			
		if(!empty($minSellData)){
			$maxSellSize = current(array_keys($minSellData, min($minSellData)));
		}else{
			$maxSellSize = 0;
		}
			
			
		/**** my custom function for Sizes with bid prices***/
		$buyDetailsSizes = array();
		if(count($prodSizeData) > 0){
			foreach($prodSizeData as $key=> $sizeList){
				$buyDetailsSize['size_id'] = $sizeList['id'];
				$buyDetailsSize['size'] = $sizeList['size'];
					if(isset($maxBidsData[$sizeList['size']])){
						$buyDetailsSize['bidprice'] = 'CA$'.$maxBidsData[$sizeList['size']];
						
					}else{ 
						$buyDetailsSize['bidprice'] = '';
					}
				$buyDetailsSizes[] = 	$buyDetailsSize;
			}
		}
       
		
		/**** my custom loop for Sizes with sell prices***/
		
		$sellDetailsSizes = array();
		if(count($prodSizeData) > 0){
			foreach($prodSizeData as $key=> $sizeList){
				$sellDetailsSize['size_id'] = $sizeList['id'];
				$sellDetailsSize['size'] = $sizeList['size'];
					if(isset($minSellData[$sizeList['size']])){
						$sellDetailsSize['ask_price'] = 'CA$'.$minSellData[$sizeList['size']];
						
					}else{ 
						$sellDetailsSize['ask_price'] = '';
					}
				$sellDetailsSizes[] = 	$sellDetailsSize;
			}
		}
		
			$file='';
				if(!empty($productDetail['product_images'])) {
					$file = $productDetail['product_images'];
					$prodImages = explode(',',$file);
					$total = count($prodImages); 
					$productDetail['product_images'] = url('/').'/'.current($prodImages);
					$last_sale_price = WebHelper::getLastSalePrice($productDetail['id']);
					if(!empty($last_sale_price)){
						$productDetail['last_sale_price'] = $last_sale_price;
					}else{
						$productDetail['last_sale_price'] = (object)[];
					}
					$relatedProductImage = array();
					if($total > 0){
						
						for($i=0;$i<$total;$i++){
							$relatedProductImages['id'] = $i+1;
							$relatedProductImages['image_url'] = url('/').'/'.$prodImages[$i];
							$relatedProductImage[] = $relatedProductImages;
					    }
						 $productDetail['product_related_images'] = $relatedProductImage;
					}else{
						$productDetail['product_related_images'] = $relatedProductImage;
					}
					
				}
				
		/**** my custom loop for order history ***/
		$orderhistoryArray = array();
		//print_r($prodOrders); die;
		if(count($prodOrders) > 0){
			foreach($prodOrders as $key=> $value){
				$ordersHistory['product_id'] = $value['product_id'];
				$ordersHistory['product_name'] = $productDetail['product_name'];
				$ordersHistory['product_images'] = $productDetail['product_images'];
				$ordersHistory['price'] = 'CA$'.$value['price'];
				$ordersHistory['customer_name'] = WebHelper::getUserName($value['user_id']);
				$ordersHistory['size'] = WebHelper::getSize($value['product_size_id'],$value['product_id']);
				
				$orderhistoryArray[] = 	$ordersHistory;
			}
		}
        //print_r($prodOrdersHistory); die;	
			$productDetail['minSellData'] = $sellDetailsSizes;
		    $productDetail['maxBidsData'] = $buyDetailsSizes;
			unset($productDetail['products_seller']);
			unset($productDetail['products_bidder']);
			unset($productDetail['product_sizes']);
			unset($productDetail['product_orders']);
			//$productDetails['productdetail'] = $productDetail;
			
			$relatedProducts = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where(['status'=>'1'])->take(5)->get()->toArray();
			if(count($relatedProducts) > 0){
			foreach($relatedProducts as $relatedProduct){
			$file='';
				if(!empty($relatedProduct['product_images'])) {
					$file = $relatedProduct['product_images'];
					$prodImages = explode(',',$file);
					$relatedProduct['product_images'] = url('/').'/'.current($prodImages);
					$relatedProduct['brand_name'] = $this->getBrandNameById($relatedProduct['brand_id']);
					$allRelatedProducts[] = $relatedProduct;
				}
			} 
			}
			
			
			$productDetails['productdetail'] = $productDetail;
			$productDetails['relatedproducts'] = $allRelatedProducts;
			if(!empty( $productDetail['product_related_images'])){
				$productDetails['outfitIdea'] = $productDetail['product_related_images'];
			}else{$productDetails['outfitIdea'] = array(); }
			$productDetails['orderhistory'] = $orderhistoryArray;
			
			print_r($productDetails); die;
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$productDetails]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product exist with this ID', 'data'=>(object)[]]);
		}
	}
	/** 
     * Product search API
     * 
     * @return search product on bases of product name, nick name, category name and brand name
	*/
	public function search(Request $request){
		$offset = $request->offset;
		$limit = $request->limit;
		$search = $request->search;
		$productCount = array();
		if(!empty($search)){
			$productArray = ProductsModel::whereHas('productBrand', function($query) use($search) {
				$query->where([['brand_name', 'like', '%'.$search.'%'],['status', '=', '1']]);
			})->orWhereHas('productCategory', function($query) use ($search) {
				$query->where([['category_name', 'LIKE', '%' . $search . '%'],['status', '=', '1']]);
			})->orWhere([['product_name','LIKE','%'.$search.'%'],['status', '=', '1']])->orWhere([['product_nick_name','LIKE','%'.$search.'%'],['status', '=', '1']]);
		}else{
			$productArray = ProductsModel::where([['status', '=', '1']]);
		}
		$countP = $productArray->get()->toArray();
		$products = $productArray->select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->offset($offset)->limit($limit)->get()->toArray();
		if(!empty($products)){
			foreach($products as $singleProduct){
			$file='';
				if(!empty($singleProduct['product_images'])) {
					$file = $singleProduct['product_images'];
					$prodImages = explode(',',$file);
					$singleProduct['product_images'] = url('/').'/'.current($prodImages);
					$singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
					$product[] = $singleProduct;
				}
			}
			$sProduct['totalCount'] = count($countP);
			$sProduct['results'] = $product;
			if($sProduct != 0){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$sProduct]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No search results available.', 'data'=>(object)[]]);
			}
		}else {
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No search results available.', 'data'=>(object)[]]);
		}
		
	}
	
	/** 
     * get sneaker API
     * 
     * @return sneakers with various filter and sorting options
	*/
	public function getSneakers(Request $request){
		$category = 'SHOES';
		$searchtext = $request['search_text']; 
		$shoesData = ProductsModel::whereHas('productCategory', function($query) use($category) {
							$query->where([['category_name', '=', $category],['status', '=', '1']]);
						});
					
		if(!empty($searchtext)){
			$shoesData = $shoesData->where(function ($query) use($searchtext) {
							$query->where('product_name','LIKE','%'.$searchtext.'%')
							 ->orWhere('product_nick_name','LIKE','%'.$searchtext.'%');
						});
		}
		//dd($shoesData); die;
		
		$products = $this->getFilterData($shoesData, $request);
		    if(!empty($products)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
			}
		
	}
	/*** Get StreetWears **/
	public function getStreetwears(Request $request){
		$category = 'street wear';
		$searchtext = $request['search_text']; 
		
		$shoesData = ProductsModel::whereHas('productCategory', function($query) use($category) {
							$query->where([['category_name', '=', $category],['status', '=', '1']]);
						});
					
		if(!empty($searchtext)){
			$shoesData = $shoesData->where(function ($query) use($searchtext) {
							$query->where('product_name','LIKE','%'.$searchtext.'%')
							 ->orWhere('product_nick_name','LIKE','%'.$searchtext.'%');
						});
		}
		$products = $this->getFilterData($shoesData, $request);
		    if(!empty($products)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
			}
		
	}
	/*** Get brandwise products **/
	public function getBrandwiseProducts(Request $request){
		$brand_id = $request['brand_id']; 
		$searchtext = $request['search_text']; 
		
		$shoesData = ProductsModel::where([['brand_id', '=', $brand_id],['status', '=', '1']]);
		if(!empty($searchtext)){
			$shoesData = $shoesData->where(function ($query) use($searchtext) {
						  $query->where('product_name','LIKE','%'.$searchtext.'%')
						  ->orWhere('product_nick_name','LIKE','%'.$searchtext.'%');
						});
		}
		//print_r($shoesData->get()->toArray()); die;
		$products = $this->getFilterData($shoesData, $request);
		    if(!empty($products)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
			}
		
	}
	/*** Get category products **/
	public function getCategorywiseProducts(Request $request){
		$category_id = $request['category_id']; 
		$searchtext = $request['search_text']; 
		
		$shoesData = ProductsModel::where([['category_id', '=', $category_id],['status', '=', '1']]);
		
		if(!empty($searchtext)){
			$shoesData = $shoesData->where(function ($query) use($searchtext) {
						  $query->where('product_name','LIKE','%'.$searchtext.'%')
						  ->orWhere('product_nick_name','LIKE','%'.$searchtext.'%');
						});
		}
		
		//print_r($shoesData->get()->toArray()); die;
		$products = $this->getFilterData($shoesData, $request);
		    if(!empty($products)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
			}
		
	}
	/***** getRecommendedProducts API ****/
	function getRecommendedProducts(Request $request){
		$searchtext = $request['search_text']; 
		$shoesData = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['status','=',1],['trending','=',0]]);
		//dd($shoesData); die;
		if(!empty($searchtext)){
			$shoesData = $shoesData->where(function ($query) use($searchtext) {
						  $query->where('product_name','LIKE','%'.$searchtext.'%')
						  ->orWhere('product_nick_name','LIKE','%'.$searchtext.'%');
						});
		}
		$products = $this->getFilterData($shoesData, $request);
		if(!empty($products)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
		}
	}
	
	/****bargain products ***/
	function getBargainProductsList(Request $request){
		$shoesData = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['status','=',1],['trending','=',0]]);
		//dd($shoesData); die;
		$products = $this->getFilterData($shoesData, $request);
		if(!empty($products)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
		}
	}
	
	/****My bargain products ***/
	function getMyBargainProducts(Request $request){
		$shoesData = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['status','=',1],['trending','=',0]]);
		$products = $this->getFilterData($shoesData, $request);
		if(!empty($products)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
		}
	}
	
	/****Backdoor Sale products ***/
	function getBackdoorSaleProducts(Request $request){
		$shoesData = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where([['status','=',1],['trending','=',0]]);
		$products = $this->getFilterData($shoesData, $request);
		$vipSale['days'] = date('d');
		$vipSale['hour'] = date('H');
		$vipSale['minute'] = date('i');
		$vipSale['second'] = date('s');
		$products['timer'] = $vipSale;
		if(!empty($products)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$products]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product found', 'data'=>(object)[]]);
		}
	}
	
	/*** Main filter function ****/
	public function getFilterData($shoesData, $requestedData){
		$brandFilter = array();
		$sizeTypeFilter = array();
		$sizeFilter = array();
		$seasonFilter = array();
		$priceRangeFilter = array();
		
		$sortBytype = $requestedData['sorting_type']; 
		$limit = $requestedData['limit']; 
		$offset = $requestedData['offset']; 
		
		if(isset($sortBytype)){
			if($sortBytype == 0){
				$shoesData = $shoesData->orderBy('trending',1);
			}
			if($sortBytype == 1){
				$highestBuyingIds = $this->getHighestBuyingProducts();
				foreach($highestBuyingIds as $highestBuyingId){
						$highestBuying[] = $highestBuyingId['id'];
					}
				$shoesData = $shoesData->whereIn('id', $highestBuying);
			}
			if($sortBytype == 2){
				$highestBuyingIds = $this->getHighestBuyingProducts();
				foreach($highestBuyingIds as $highestBuyingId){
						$highestBuying[] = $highestBuyingId['id'];
					}
				$shoesData = $shoesData->whereIn('id', $highestBuying);
			}
			if($sortBytype == 3){
				$shoesData = $shoesData->orderBy('release_date','ASC');
			}
		}
		   
		if(isset($requestedData['filter_type'])){
		  // print_r($shoesData->get()->toArray()); exit;
		   $filters = $requestedData['filter_type'];
		   if(!empty($filters)){
			foreach($filters as $key => $value) {
				if($key == 'brand'){
					$collection = collect($value);
					$plucked = $collection->pluck('id');
					$brandFilter = $plucked->all();
					//print_r($brandFilter); exit;
					$shoesData = $shoesData->whereIn('brand_id', $brandFilter);
					
				}
				if($key == 'size_types'){
					$collection = collect($value);
					$plucked = $collection->pluck('id');
					$sizeTypeFilter = $plucked->all();
					$shoesData = $shoesData->whereIn('size_type_id', $sizeTypeFilter); 
					//print_r($shoesData->get()->toArray()); exit;
				}
				if($key == 'size'){
					$collection = collect($value);
					$plucked = $collection->pluck('size');
					$productSizes = $plucked->all();
					$shoesData = $shoesData->whereHas('productSizes', function($query) use($productSizes) {
							$query->whereIn('size',  $productSizes);
						});
					//print_r($shoesData->get()->toArray()); exit;
				}
				if($key == 'season'){
					$seasonFilter = $value['value']; 
					$shoesData = $shoesData->where('season', $seasonFilter);
				}
				if($key == 'price_range'){
					$prices = $value;
					$x = explode('-', $prices['value']);
					$min = $x[0]; 
					$max = $x[1]; 
					$shoesData = $shoesData->whereBetween('retail_price', [$min,$max]);
				}
			}
		}
	}     
	
	$productsC = $shoesData->get()->toArray();
	$products = $shoesData->select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->offset($offset)->limit($limit)->get()->toArray();
	$product = array();
	//print_r($products); die;
	foreach($products as $singleProduct){
		$file='';
			if(!empty($singleProduct['product_images'])) {
				$file = $singleProduct['product_images'];
				$prodImages = explode(',',$file);
				$singleProduct['product_images'] = url('/').'/'.current($prodImages);
				$singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
				$product[] = $singleProduct;
			}
		}
	$productsData['totalCount'] = count($productsC);
	$productsData['results'] = $product;
		if(count($product)>0){
			return $productsData;
		}else{
			return $product;
		}
		
	}
	/**** highest buying product ***/
	public function getHighestBuyingProducts(){
		$getHighestBuyingProducts = ProductsModel::where([['trending', '=', '1'],['status', '=', '1']])->get()->toArray();
		return $getHighestBuyingProducts;
	}
	
	
	
	/*** Get Filter Data **/
	public function getFilters(Request $request){
		$type  = $request['type'];
		$filter = array();
		if($type == 'Sneakers'){
			$filter['brands'] = BrandsModel::whereHas('categoryBrands' , function($q){
					$q->where('category_id', '=', 1);
				})->select('id','brand_name')->get()->toArray();
			// $filter['brands'] = BrandsModel::with(['categoryBrands'])->get()->toArray();
			$filter['sizeTypes'] = ProductSizeTypes::select('id','size_type')->where('status','=',1)->get()->toArray();
			$filter['prices'] = PricesModel::select('id','price_range')->get()->toArray();
			$filter['years'] = YearsModel::select('id','release_year')->get()->toArray();
		}
		if($type == 'Streetwear'){
			$filter['brands'] = BrandsModel::whereHas('categoryBrands' , function($q){
					$q->where('category_id', '=', 7);
				})->select('id','brand_name')->get()->toArray();
			$filter['sizeTypes'] = ProductSizeTypes::select('id','size_type')->where('status','=',1)->get()->toArray();
			$filter['prices'] = PricesModel::select('id','price_range')->get()->toArray();
			$filter['seasons'] = SeasonsModel::select('id','season')->get()->toArray();
			$filter['streetwearSizes'] = StreetwearSizesModel::select('id','full_size','size')->get()->toArray();
		}
		if(!empty($filter)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Filter retrieved succesfully.', 'data'=>$filter]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.', 'data'=>(object)[]]);
		}
		
		
	}
	public function GetTrendingProducts(){
		$trendingProducts = ProductsModel::select('brand_id')->where([['trending', '=', '1'],['status', '=', '1']])->get()->toArray();
		$collection = new Collection($trendingProducts);
		$uniqueItems = $collection->unique();
		foreach($uniqueItems as $singleProduct){
			    $singleProduct['brand_name'] = $this->getBrandNameById($singleProduct['brand_id']);
				$product[] = $singleProduct;
			}
		
		
		if(!empty($product)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Retrieved succesfully.', 'data'=>$product]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.', 'data'=>(object)[]]);
		}
		
		
	}
	/*** common functions **/
	public function getBrandNameById($id){
		$brands = BrandsModel::where("id", $id)->get()->first();
		return $brands['brand_name'];
	}
	/*** function to get blog detail **/
	public function getBlogDetail(Request $request){
		$id  = $request['id'];
		$Blog = BlogsModel::where("id", $id)->get()->first();
		$Blog->image_url = url('/').'/'.$Blog->image_url; 
		if(!empty($Blog)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Blog Info retrieved succesfully.', 'data'=>$Blog]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No blog exist with this ID', 'data'=>(object)[]]);
		}
	}
	public function getBlogList(Request $request){
		$offset = $request->offset;
		$limit = $request->limit;
		$totalBlogs = BlogsModel::get()->toArray();
		$blogs = BlogsModel::select('id','title','image_url','description')->offset($offset)->limit($limit)->get()->toArray();
			$getBlogs = array();
			foreach($blogs as $blog){
			$file='';
				if(!empty($blog['image_url'])) {
					$file = $blog['image_url'];
					$prodImages = explode(',',$file);
					$blog['image_url'] = url('/').'/'.current($prodImages);
					$blog['web_url'] = '';
					$getBlogs[] = $blog;
				}
			}
		$blogsData['totalCount'] = count($totalBlogs);
		$blogsData['results'] = $getBlogs;
			if($blogsData != 0){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$blogsData]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No result found.', 'data'=>(object)[]]);
			}
	}
	
	/** 
     * Product detail API
     * 
     * @return product detail with related products
	*/
	public function bargainProductDetail(Request $request){
		$productDetails = array();
		$productID = $request->product_id;
		if (ProductsModel::where('id', '=', $productID)->exists()) {
			
			$productDetail = ProductsModel::with(['productCategory','productBrandType',
			'productBrand','productSizeTypes','productSizes','productsBidder','productsSeller','productOrders'])->findOrFail($productID)->toArray();
			//$productDetail = $productDetails['productDetails'];
			$bidData = $productDetail['products_bidder'];
			$sellData = $productDetail['products_seller'];
			$prodSizeData = $productDetail['product_sizes'];
			$prodOrders = $productDetail['product_orders'];
			
		//print_r($bidData); die;
			
		// getting All sizes and its primaryID
		foreach($prodSizeData as $key=>$value){
			$sizeArray[$value['id']] = $value['size'];
		}
		
		// Merging Primary
		if(!empty($bidData)){
			foreach($bidData as $key=>$value){
				$size = $sizeArray[$value['size_id']];
				$finalBidData[$size][] = $value['bid_price'];
				//$finalBid[$size]['seller_id'] = $value['id'];
				//$finalBid[$size]['size'] = $size;
				//$finalBid[$size]['price'] = $value['bid_price'];
				//$finalBidData[] = $finalBid;
				
			}
		}else{
			$finalBidData = array();
		}
		
		// Merging Primary IDs for Sell
		
		if(!empty($sellData)){
			foreach($sellData as $key=>$value){
				$size = $sizeArray[$value['size_id']];
				$finalSellData[$size][] = $value['ask_price'];
				
			}
		}else{
			$finalSellData = array();
		}
			
		// fetching Max Bid from all the Bid Data
		if(!empty($finalBidData)){
			foreach($finalBidData as $key=>$value){
				$maxBidsData[$key] = max($value);
			}
		}else{
			$maxBidsData = array();
		}
		
		
		if(!empty($finalSellData)){
			foreach($finalSellData as $key=>$value){
				$minSellData[$key] = min($value);
			}
		}else{
			$minSellData = array();
		}
			
		if(!empty($maxBidsData)){
			$minBidSize = current(array_keys($maxBidsData, min($maxBidsData)));
		}else{
			$minBidSize = 0;
		}
			
			
		if(!empty($minSellData)){
			$maxSellSize = current(array_keys($minSellData, min($minSellData)));
		}else{
			$maxSellSize = 0;
		}
			
			
		/**** my custom function for Sizes with bid prices***/
		$buyDetailsSizes = array();
		if(count($prodSizeData) > 0){
			foreach($prodSizeData as $key=> $sizeList){
				$buyDetailsSize['size_id'] = $sizeList['id'];
				$buyDetailsSize['size'] = $sizeList['size'];
					if(isset($maxBidsData[$sizeList['size']])){
						$buyDetailsSize['bidprice'] = $maxBidsData[$sizeList['size']];
						
					}else{ 
						$buyDetailsSize['bidprice'] = '';
					}
				$buyDetailsSizes[] = 	$buyDetailsSize;
			}
		}
       
		
		/**** my custom loop for Sizes with sell prices***/
		
		$sellDetailsSizes = array();
		if(count($prodSizeData) > 0){
			foreach($prodSizeData as $key=> $sizeList){
				$sellDetailsSize['size_id'] = $sizeList['id'];
				$sellDetailsSize['size'] = $sizeList['size'];
					if(isset($minSellData[$sizeList['size']])){
						$sellDetailsSize['ask_price'] = $minSellData[$sizeList['size']];
						
					}else{ 
						$sellDetailsSize['ask_price'] = '';
					}
				$sellDetailsSizes[] = 	$sellDetailsSize;
			}
		}
		
			$file='';
				if(!empty($productDetail['product_images'])) {
					$file = $productDetail['product_images'];
					$prodImages = explode(',',$file);
					$total = count($prodImages); 
					$productDetail['product_images'] = url('/').'/'.current($prodImages);
					$last_sale_price = WebHelper::getLastSalePrice($productDetail['id']);
					if(!empty($last_sale_price)){
						$productDetail['last_sale_price'] = $last_sale_price;
					}else{
						$productDetail['last_sale_price'] = (object)[];
					}
					$relatedProductImage = array();
					if($total > 0){
						
						for($i=0;$i<$total;$i++){
							$relatedProductImages['id'] = $i;
							$relatedProductImages['image_url'] = url('/').'/'.$prodImages[$i];
							$relatedProductImage[] = $relatedProductImages;
					    }
						 $productDetail['product_related_images'] = $relatedProductImage;
					}else{
						$productDetail['product_related_images'] = $relatedProductImage;
					}
					
				}
				
		/**** my custom loop for order history ***/
		$orderhistoryArray = array();
		//print_r($prodOrders); die;
		if(count($prodOrders) > 0){
			foreach($prodOrders as $key=> $value){
				$ordersHistory['product_id'] = $value['product_id'];
				$ordersHistory['product_name'] = $productDetail['product_name'];
				$ordersHistory['product_images'] = $productDetail['product_images'];
				$ordersHistory['price'] = $value['price'];
				$ordersHistory['customer_name'] = WebHelper::getUserName($value['user_id']);
				$ordersHistory['size'] = WebHelper::getSize($value['product_size_id'],$value['product_id']);
				
				$orderhistoryArray[] = 	$ordersHistory;
			}
		}
        //print_r($prodOrdersHistory); die;	
			$productDetail['minSellData'] = $sellDetailsSizes;
		    $productDetail['maxBidsData'] = $buyDetailsSizes;
			unset($productDetail['products_seller']);
			unset($productDetail['products_bidder']);
			unset($productDetail['product_sizes']);
			unset($productDetail['product_orders']);
			//$productDetails['productdetail'] = $productDetail;
			
			$relatedProducts = ProductsModel::select('id','product_name','color','brand_id','retail_price','product_images','start_counter')->where(['status'=>'1'])->take(5)->get()->toArray();
			if(count($relatedProducts) > 0){
			foreach($relatedProducts as $relatedProduct){
			$file='';
				if(!empty($relatedProduct['product_images'])) {
					$file = $relatedProduct['product_images'];
					$prodImages = explode(',',$file);
					$relatedProduct['product_images'] = url('/').'/'.current($prodImages);
					$relatedProduct['brand_name'] = $this->getBrandNameById($relatedProduct['brand_id']);
					$allRelatedProducts[] = $relatedProduct;
				}
			}
			}
			
			
			$productDetails['productdetail'] = $productDetail;
			$productDetails['relatedproducts'] = $allRelatedProducts;
			if(!empty( $productDetail['product_related_images'])){
				$productDetails['outfitIdea'] = $productDetail['product_related_images'];
			}else{$productDetails['outfitIdea'] = array(); }
			$productDetails['orderhistory'] = $orderhistoryArray;
			
			
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Products retrieved succesfully.', 'data'=>$productDetails]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No product exist with this ID', 'data'=>(object)[]]);
		}
	}
	
	
}
