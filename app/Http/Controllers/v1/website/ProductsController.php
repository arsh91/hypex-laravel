<?php

namespace App\Http\Controllers\v1\website;

use App\Models\CategoryBrands;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Http\Requests\SaveBidRequest;
use App\Http\Requests\SaveSellRequest;
use App\Http\Requests\PurchaseBidRequest;
use App\Http\Requests\SellBidRequest;
use Carbon\Carbon;
use Spatie\ArrayToXml\ArrayToXml;
use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use App\Models\BrandsModel;
use App\Models\CategoriesModel;
use App\Models\ProductSizeTypes;
use App\Models\UsersBillingAddressModel;
use App\Models\UsersShippingAddressModel;
use App\Models\ProductsBidder;
use App\Models\ProductsSeller;
use App\Models\SubscriptionsModel;
use App\Models\OrdersModel;
use App\Models\ProvincesModel;
use App\Models\CountriesModel;
use App\Models\VipSaleCounter;
use App\Helpers\WebHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\User;
use Currency;

class ProductsController extends Controller
{
	protected $gateway;
	protected $options;
	protected $Timeout = false;

	public function getAllShoes()
	{

		$shoesData = array();
		$brand_ids = [];
		$category_ids = [];
		$brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
		$category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		$shoesData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status' => '1','vip_status' => '0'])->orderBy('updated_at', 'DESC')->paginate();
		


		$productsData = $shoesData['paginate']->toArray();

		$shoesData['allProducts'] = $productsData['data'];

		$shoesData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

		$shoesData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
		//dd($shoesData);
		// exit();
		return view('website.all-shoes', $shoesData);

	}

	/*Get All T-Shirts*/
	public function getAllTshirts()
	{
		$tshirtData = array();
		$tshirtID = 2; //will make it dynamic later when category image functionality is builded

		$tshirtData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1'])->where(['category_id' => $tshirtID])->orderBy('updated_at', 'DESC')->paginate();

		$productsData = $tshirtData['paginate']->toArray();

		$tshirtData['allProducts'] = $productsData['data'];

		$tshirtData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

		$tshirtData['allSizeTypes'] = ProductSizeTypes::all()->toArray();
		// dd($shoesData);
		// exit();
		return view('website.all-tshirts', $tshirtData);
	}

	/*Get products respective to categories*/
	public function getCategoryListing($queryString = null, Request $request){
		$catData = array();
		$brand_ids = [];
		$category_ids = [];
		$categoryID = base64_decode($queryString);

		//$brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
		//$category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();

		//fetch those brands which are active under this CAT_ID
		$brands_id = CategoryBrands::select('brand_id')->where(['status'=> 1])->where(['category_id'=> $categoryID])->get()->toArray();

		$brand_ids = [];

		//loop to pass the brand IDs to fetch products
		foreach($brands_id as $brand_id){
			$brand_ids[] = $brand_id['brand_id'];
		}

//		print_r($brands_id); die;

		$catData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->wherein('brand_id', $brand_ids )->where('category_id',$categoryID)->where(['status' => '1'])->orderBy('updated_at', 'DESC')->paginate();


		$productsData = $catData['paginate']->toArray();

		$catData['allProducts'] = $productsData['data'];

		//previous query to fetch data of brands table
		//$catData['allBrands'] = BrandsModel::where(['status' => '1'])->get()->toArray();

		$catData['allSizeTypes'] = ProductSizeTypes::all()->toArray();


		//New query to fetch brands using categoryID in an array
		$brandsBycategoryID = array();
		foreach ($brand_ids as $brandID){
			$brandData = BrandsModel::where(['id' => $brandID])->where(['status' => '1'])->get()->toArray();
			$brandsBycategoryID[] = $brandData[0];
		}
		$catData['allBrands'] = $brandsBycategoryID;

		//Set category ID and use it on frontend
		$catData['category_id'] = base64_encode($categoryID);

		return view('website.category-products', $catData); //
	}


	public function getProductDetail($queryString = null, Request $request)
	{
		//GET THE CURRENCY CODE IN SESSION
		$session_currency_code = '';
		if ($request->session()->has('currencyCode')) {
			$session_currency_code = $request->session()->get('currencyCode');
		} else {
			$session_currency_code = 'CAD';
		}

		$productDetails = array();
		$productID = base64_decode($queryString);
		$finalBidData = array();
		$finalSellData = array();

		$productDetails['maxBidsData'] = array();
		$productDetails['minBidSize'] = '';

		$productDetails['productDetails'][] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes',
			'productSizes',
			'productsBidder',
			'productsSeller'
		])
			->findOrFail($productID)
			->toArray();

		$prodDetails = $productDetails['productDetails'];
		$splitData = current($prodDetails);
		$bidData = $splitData['products_bidder'];
		$sellData = $splitData['products_seller'];
		$prodSizeData = $splitData['product_sizes'];
                
                $brandId = $splitData['brand_id'];
		$categoryId = $splitData['category_id'];
		$brandTypeId = $splitData['brand_type_id'];
      
		// getting All sizes and its primaryID
		foreach ($prodSizeData as $key => $value) {
			$sizeArray[$value['id']] = $value['size'];
		}

		/* Merging Primary
		*TO SHOW OFFERS IN "Sell offer / sell now"
		 *comment:- it's good to change the price here with currency code before it sending to front
		*/
		if (!empty($bidData)) {
			$i = 0;
			foreach ($bidData as $key => $value) {

				$expiryDate = $value['bid_expiry_date'];
				$todayDate = date('Y-m-d h:i:s');
				if ($expiryDate < $todayDate) {
					continue;
				}
				$size = $sizeArray[$value['size_id']];
				$finalBidData[$size][] = $value['bid_price'];

				/**Below is the LOGIC depends for CURRENCY CHANGE**/
				//$finalBidData[$size][$i]['price'] = $value['bid_price'];
				//$finalBidData[$size][$i]['currency_code'] = $value['currency_code'];

				//if database currency code is different to session currency code then change price acc to exchange rate
				/*if ($value['currency_code'] != $session_currency_code) {
					//echo "currency is diff";
					//$finalBidData[$size][] = WebHelper::changePriceWithoutCurrency($session_currency_code, $value['currency_code'], $value['bid_price']);
					$convertedData = Currency::convert($session_currency_code, $value['currency_code'],
						$value['bid_price']);
					//print_r($convertedData); die;
					//$finalBidData[$size][] = $convertedData['convertedAmount'];;
				} else {
					//echo "currency in session and currency from db is same<br>-----------";
					$finalBidData[$size][] = $value['bid_price'];
				}*/
				$i++;
			}
		} else {
			$finalBidData = array();
		}


		/*Merging Primary IDs for Sell
		 * To Show offers in MAKE BUY OFFER / BUY NOW
		 * */
		if (!empty($sellData)) {
			$i = 0;
			foreach ($sellData as $key => $value) {

				$expiryDate = $value['sell_expiry_date'];
				$todayDate = date('Y-m-d h:i:s');
				if ($expiryDate < $todayDate) {
					continue;
				}

				$size = $sizeArray[$value['size_id']];
				$finalSellData[$size][] = $value['ask_price'];

				//BELOW LOGIC TO APPLY CURRENCY LOGIC
				//$finalSellData[$size][$i]['price'] = $value['ask_price'];
				//$finalSellData[$size][$i]['currency_code'] = $value['currency_code'];
				/*if ($value['currency_code'] != $session_currency_code) {
					$convertedData = Currency::convert($session_currency_code, $value['currency_code'],
						$value['ask_price']);
					$finalSellData[$size][] = $convertedData['convertedAmount'];
					//$finalSellData[$size][] = WebHelper::changePriceWithoutCurrency($session_currency_code, $value['currency_code'], $value['ask_price']);
				} else {
					$finalSellData[$size][] = $value['ask_price'];
				}*/
				$i++;
			}
		} else {
			$finalSellData = array();
		}

		//echo "<pre>"; print_r($finalSellData); echo "</pre>"; die;
		/* fetching Max Bid from all the Bid Data to show in seller popup
		* SELL OFFER/ SELL NOW
		 * */
		if (!empty($finalBidData)) {
			foreach ($finalBidData as $key => $value) {
				$maxBidsData[$key] = max($value);
			}
		} else {
			$maxBidsData = array();
		}

		if (!empty($finalSellData)) {
			foreach ($finalSellData as $key => $value) {
				$minSellData[$key] = min($value);
			}
		} else {
			$minSellData = array();
		}

		$productDetails['minSellData'] = $maxBidsData;
		$productDetails['maxBidsData'] = $minSellData;
		if (!empty($maxBidsData)) {
			$productDetails['minBidSize'] = current(array_keys($maxBidsData, min($maxBidsData)));
		} else {
			$productDetails['minBidSize'] = 0;
		}


		if (!empty($minSellData)) {
			$productDetails['maxSellSize'] = current(array_keys($minSellData, min($minSellData)));
		} else {
			$productDetails['maxSellSize'] = 0;
		}

    
            
                $productDetails['relatedProducts'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1','vip_status' => '0','brand_id'=>$brandId,'brand_type_id'=>$brandTypeId,'category_id'=>$categoryId])->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();


		return view('website.product-detail', $productDetails);

	}

	//Vip  product detail

	public function getVipProductDetail($queryString = null, Request $request)
	{
		//GET THE CURRENCY CODE IN SESSION
		$session_currency_code = '';
		if ($request->session()->has('currencyCode')) {
			$session_currency_code = $request->session()->get('currencyCode');
		} else {
			$session_currency_code = 'USD';
		}
		//echo "sesson code is --------" . $session_currency_code;
		$productDetails = array();
		$productID = base64_decode($queryString);

		$productDetails['productDetails'][] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes',
			'productSizes',
			'productsBidder',
			'productsSeller'
		])
			->findOrFail($productID)
			->toArray();

		$prodDetails = $productDetails['productDetails'];
		$splitData = current($prodDetails);
		$prodSizeData = $splitData['product_sizes'];

		// getting All sizes and its primaryID
		foreach ($prodSizeData as $key => $value) {
			$sizeArray[$value['id']] = $value['size'];
		}

		$userId = Auth::id();

		$date = new \DateTime();
		$date->modify('-24 hours');
		$formatted_date = $date->format('Y-m-d H:i:s');
		if($userId){
			//$userSubcribed = User::with(['subcription'])->where('id', $userId)->get()->first()->toArray();
			$userSubcribed = SubscriptionsModel::where(['user_id' => $userId , 'status' => 1])->get()->toArray();
			if(empty($userSubcribed)){
				//echo "please pay";
				$productDetails['subcriptioncheck'] = 0;  // 0 is Please buy subscription
			}else{
				$vipCountertime = VipSaleCounter::whereDate('start_date', '<=', date("Y-m-d"))->whereDate('end_date', '>=', date("Y-m-d"))->first();
				//dump($vipCountertime);
				if(empty($vipCountertime)){
					//echo "sale not active";
					$productDetails['subcriptioncheck'] = 4; // 4 is sale is not active yet.
				}else{
					//echo "sale active";
					//echo "you alreday have";
					$userOrder = OrdersModel::where(['user_id' => $userId , 'status' => 1,'vip_order' => 1])->where('created_at', '>',$formatted_date)->get()->toArray();
					if(empty($userOrder)){
					$productDetails['subcriptioncheck'] = 1; // 1 is User subcription already buyed
					}else{
					$productDetails['subcriptioncheck'] = 3; // 3 is Already bought 1 product between 24 hour
					}
				}
				
			}
        }else{
			$productDetails['subcriptioncheck'] = 2; // 2 is please login first
		}

		$productDetails['relatedProducts'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->where(['status' => '1','vip_status' => '1'])->orderBy('updated_at', 'DESC')->limit(10)->get()->toArray();

		return view('website.vip-product-detail', $productDetails);

	}


	/*
	 * filter general products
	 * @Params brand_id, category_id
	 * @Returns filtered product list
	*/
	public function filterProducts(Request $request)
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


	public function bidProduct(Request $request, $id = null, $size = null, $type = null)
	{
		//dump($request->session()->all());
		$user_id = Auth::id();

		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productsBidder',
				'productBrandType'
			])->findOrFail($productID)->toArray();

			if (is_array($productDetails) && !empty($productDetails)) {

				$bidsData = WebHelper::getProductBidsData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $bidsData;
				$productDetailsData['pageType'] = $type;

				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);


				}else{

					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					
					if(!isset($userShippingAddr[0]['id'])){
						$userShippingAddr[] = array('id'=>'', 'first_name'=>'', 'last_name'=>'', 'full_address'=>'', 'street_city'=>'', 'phone_number'=>'', 'country'=>'', 'province'=>'', 'zip_code'=>'');
					}
					//if(!isset($userShippingAddr[0]['id'])){
					//	return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					//}
					//$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// RETURN adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{

					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					
					if(!isset($userReturnAddr[0]['id'])){
						$userReturnAddr[] = array('id'=>'', 'first_name'=>'', 'last_name'=>'', 'full_address'=>'', 'street_city'=>'', 'phone_number'=>'', 'country'=>'', 'province'=>'', 'zip_code'=>'');
					}

					//if(!isset($userReturnAddr[0]['id'])){
					//	return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					//}

					//$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}

				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;
				
				$allBidsData = $productDetailsData['maxBidsData'];
				$productDetailsData['productDetails']['sellerOffer'] = false; // to check if user can "Buy Now"
				if (isset($allBidsData[$size])) {
					$productDetailsData['productDetails']['sellerOffer'] = true;
					$selectedBidPrice = $allBidsData[$size];

					$request->session()->put('pricePurchase', $selectedBidPrice);
					$request->session()->put('product_id', $productID);
					$request->session()->put('product_size_id', $sizeID);

					$productSellerId = WebHelper::getProductSellerId($productID, $sizeID, $selectedBidPrice);
					$request->session()->put('product_seller_id', $productSellerId);
				}
				
				
				return view('website.bid-product', $productDetailsData);
			}

		}

	}

	///// start we pay module


	public function wepaytest(Request $request)
	{
		$gateway = Omnipay::create('WechatPay_App');
		$gateway->setAppId($config['app_id']);
		$gateway->setMchId($config['mch_id']);
		$gateway->setApiKey($config['api_key']);

		$order = [
			'body' => 'The test order',
			'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
			'total_fee' => 1, //=0.01
			'spbill_create_ip' => 'ip_address',
			'fee_type' => 'CNY'
		];

		/**
		 * @var Omnipay\WechatPay\Message\CreateOrderRequest $request
		 * @var Omnipay\WechatPay\Message\CreateOrderResponse $response
		 */
		$request = $gateway->purchase($order);
		$response = $request->send();

		//available methods
		$response->isSuccessful();
		$response->getData(); //For debug
		$response->getAppOrderData(); //For WechatPay_App
		$response->getJsOrderData(); //For WechatPay_Js
		$response->getCodeUrl(); //For Native Trade Type
		return view('website.wepaytest');
	}


	////// End we pay module

	/*Method runs on submitting "MAKE BID OFFER"
		* @Params SaveBidRequest | formdata
		 * @Returns payment redirection
		 * */
	public function saveBid(SaveBidRequest $request)
	{
		//dd('method hits');
		$validated = $request->validated();
		$user_id = Auth::id();
		$saveUsersShippingAddress = UsersShippingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],
														[
															'first_name' => $validated['shipping_first_name'],
															'last_name' => $validated['shipping_last_name'],
															'phone_number' => $validated['shipping_phone'],
															'province' => $validated['shipping_province'],
															'full_address' => $validated['shipping_full_address'],
															'street_city' => $validated['shipping_street_city'],
															'country' => $validated['shipping_country'],
															'zip_code' => $validated['shipping_zip'],
															'status' => 1
														]);

		if ($saveUsersShippingAddress->id) {
			$request->session()->put('shippingAddressId', $saveUsersShippingAddress->id);
			$saveUsersBillingAddress = UsersBillingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],
														[
															'first_name' => $validated['shipping_first_name'],
															'last_name' => $validated['shipping_last_name'],
															'phone_number' => $validated['shipping_phone'],
															'province' => $validated['shipping_province'],
															'full_address' => $validated['shipping_full_address'],
															'street_city' => $validated['shipping_street_city'],
															'country' => $validated['shipping_country'],
															'zip_code' => $validated['shipping_zip'],
															'status' => 1
														]);

			if ($saveUsersBillingAddress->id) {
				$request->session()->put('billingAddressId', $saveUsersBillingAddress->id);

				$bidDays = $validated['bid_days'];
				$expiryDate = Carbon::now()->addDays($bidDays);


				$shippingAddressId = $request->session()->get('shippingAddressId');
				$billingAddressId = $request->session()->get('billingAddressId');
				$shipping_price = $request->session()->get('shippingRate');
				$total_price = $request->session()->get('totalPrice');
				$commission_price = $request->session()->get('commissionPrice');
				$processing_fee = $request->session()->get('processingFee');

				$bidderPrimaryID = ProductsBidder::create([
					'user_id' => $user_id,
					'product_id' => $validated['hiddenProdId'],
					'size_id' => $validated['hiddenSizeId'],
					'actual_price' => $validated['hiddenPrice'],
					'bid_price' => $validated['bid_price'],
					'shipping_price' => $shipping_price,
					'total_price' => $total_price,
					'commission_price' => $commission_price,
					'processing_fee' => $processing_fee,
					'billing_address_id' => $billingAddressId,
					'shiping_address_id' => $shippingAddressId,
					'bid_expiry_date' => $expiryDate,
					'currency_code' => $validated['hiddenCurrency'],
					'status' => 0
				]);
 
				// this is temprory due to strict deadline, need
				// to modify with sessions
				$request->session()->put('bidPrimaryId', $bidderPrimaryID->id);
				$request->session()->put('type', 'bid');
				$request->session()->put('primary', $bidderPrimaryID->id);
				$validated['type'] = base64_encode('bid');
				$validated['primary'] = base64_encode($bidderPrimaryID->id);

				return view('website.bid-sell-payment', $validated);
			}

		}

	}


	public function sellProduct(Request $request , $id = null, $size = null, $type = null)
	{
		$user_id = Auth::id();
		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productsBidder',
				'productBrandType'
			])->findOrFail($productID)->toArray();

			if (is_array($productDetails) && !empty($productDetails)) {

				$bidsData = WebHelper::getProductBidsData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $bidsData;
				$productDetailsData['pageType'] = $type;
				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();

				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);

				}else{
					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();

					if(!isset($userShippingAddr[0]['id'])){
						$userShippingAddr[] = array('id'=>'', 'first_name'=>'', 'last_name'=>'', 'full_address'=>'', 'street_city'=>'', 'phone_number'=>'', 'country'=>'', 'province'=>'', 'zip_code'=>'');
					}

					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// return adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{

					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					
					if(!isset($userReturnAddr[0]['id'])){
						$userReturnAddr[] = array('id'=>'', 'first_name'=>'', 'last_name'=>'', 'full_address'=>'', 'street_city'=>'', 'phone_number'=>'', 'country'=>'', 'province'=>'', 'zip_code'=>'');
					}

					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}
				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;

				$allBidsData = $productDetailsData['minSellData'];
				$productDetailsData['productDetails']['sellerOffer'] = false; // to check if user can "Buy Now"
				if (isset($allBidsData[$size])) {
					$productDetailsData['productDetails']['sellerOffer'] = true;
					$selectedBidPrice = $allBidsData[$size];

					$request->session()->put('pricePurchase', $selectedBidPrice);
					$request->session()->put('product_id', $productID);
					$request->session()->put('product_size_id', $sizeID);

					$productSellerId = WebHelper::getProductSellerId($productID, $sizeID, $selectedBidPrice);
					$request->session()->put('product_seller_id', $productSellerId);
				}
				//dump($productDetailsData);
				return view('website.sell-product', $productDetailsData);

			}

		}

	}

	/* This method runs on submitting "MAKE SELL OFFER"
	 * @Params saveSellRequest | form request
	 * @Returns payment page redirection
	 * */
	public function saveSell(SaveSellRequest $request)
	{
		$validated = $request->validated();
		$user_id = Auth::id();
		$saveUsersShippingAddress = UsersShippingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],[
															'first_name' => $validated['shipping_first_name'],
															'last_name' => $validated['shipping_last_name'],
															'phone_number' => $validated['shipping_phone'],
															'province' => $validated['shipping_province'],
															'full_address' => $validated['shipping_full_address'],
															'street_city' => $validated['shipping_street_city'],
															'country' => $validated['shipping_country'],
															'zip_code' => $validated['shipping_zip'],
															'status' => 1
														]);

		if ($saveUsersShippingAddress->id) {

			$request->session()->put('shippingAddressId', $saveUsersShippingAddress->id);
			$saveUsersBillingAddress = UsersBillingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],[
															'first_name' => $validated['billing_first_name'],
															'last_name' => $validated['billing_last_name'],
															'phone_number' => $validated['billing_phone'],
															'province' => $validated['billing_province'],
															'full_address' => $validated['billing_full_address'],
															'street_city' => $validated['billing_street_city'],
															'country' => $validated['billing_country'],
															'zip_code' => $validated['billing_zip'],
															'status' => 1
														]);

			if ($saveUsersBillingAddress->id) {
				
				$request->session()->put('billingAddressId', $saveUsersBillingAddress->id);
				$bidDays = 15;
				//$shippingAddressId = $saveUsersShippingAddress->id;
				//$billingAddressId = $saveUsersBillingAddress->id;
				$bidDays = $validated['bid_days'];

				$expiryDate = Carbon::now()->addDays($bidDays);
				$shippingAddressId = $request->session()->get('shippingAddressId');
				$billingAddressId = $request->session()->get('billingAddressId');
				$shipping_price = $request->session()->get('shippingRate');
				$total_price = $request->session()->get('totalPrice');
				$commission_price = $request->session()->get('commissionPrice');
				$processing_fee = $request->session()->get('processingFee');

				//SAVE entries to product_seller table
				$sellerPrimaryID = ProductsSeller::create([
					'user_id' => $user_id,
					'product_id' => $validated['hiddenProdId'],
					'size_id' => $validated['hiddenSizeId'],
					'actual_price' => $validated['hiddenPrice'],
					'ask_price' => $validated['bid_price'],
					'shipping_price' => $shipping_price,
					'total_price' => $total_price,
					'commission_price' => $commission_price,
					'processing_fee' => $processing_fee,
					'billing_address_id' => $billingAddressId,
					'shiping_address_id' => $shippingAddressId,
					'sell_expiry_date' => $expiryDate,
					'currency_code' => $validated['hiddenCurrency'],
					'status' => 0
				]);

				// this is temprory due to strict deadline, need
				// to modify with sessions
				$request->session()->put('sellPrimaryId', $sellerPrimaryID->id);
				$request->session()->put('type', 'sell');
				$request->session()->put('primary', $sellerPrimaryID->id);
				$validated['type'] = base64_encode('sell');
				$validated['primary'] = base64_encode($sellerPrimaryID->id);

				return view('website.bid-sell-payment', $validated);
			}

		}

	}


	public function buyProduct(Request $request, $id = null, $size = null, $type = null)
	{
		$user_id = Auth::id();
		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productsBidder',
				'productBrandType'
			])->findOrFail($productID)->toArray();

			if (is_array($productDetails) && !empty($productDetails)) {

				$bidsData = WebHelper::getProductBidsData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $bidsData;


				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);

				}else{

					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();

					if(!isset($userShippingAddr[0]['id'])){
						return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					}
					
					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// return adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{


					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();

					if(!isset($userReturnAddr[0]['id'])){
						return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					}
					
					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}

				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;
				$allBidsData = $productDetailsData['maxBidsData'];
				if (isset($allBidsData[$size])) {
					$selectedBidPrice = $allBidsData[$size];
				} else {
					$notAllowed = array();
					return view('website.not-allowed', $notAllowed);
				}
				$request->session()->put('pricePurchase', $selectedBidPrice);
				$request->session()->put('product_id', $productID);
				$request->session()->put('product_size_id', $sizeID);

				$productSellerId = WebHelper::getProductSellerId($productID, $sizeID, $selectedBidPrice);
				$request->session()->put('product_seller_id', $productSellerId);
				//echo "<pre>"; print_r($productDetailsData); echo "</pre>";
				return view('website.direct-purchase', $productDetailsData);

			}

		}

	}

	public function buyPromoProduct(Request $request, $id = null, $size = null, $type = null)
	{	
		$user_id = Auth::id();

		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productBrandType'
			])->findOrFail($productID)->toArray();

			if (is_array($productDetails) && !empty($productDetails)) {

				$productData = WebHelper::getProductPromoData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $productData;
				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);

				}else{

return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// return adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{

return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}

				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;
				$passValue = $request->session()->get('pass_value');
				$request->session()->put('product_id', $productID);
				$request->session()->put('product_size_id', $sizeID);
				return view('website.promo-purchase', $productDetailsData);

			}

		}

	}

	// Vip purchase

	public function buyVipProduct(Request $request, $id = null, $size = null, $type = null)
	{
		$user_id = Auth::id();
		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productBrandType'
			])->findOrFail($productID)->toArray();
			if (is_array($productDetails) && !empty($productDetails)) {

				$productData = WebHelper::getProductPromoData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $productData;

				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);

				}else{

return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// return adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{
return redirect('add-shipping-address')->with('success','Please update your PROFILE,SHIPPING & BILLING Address before further action!');
					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}

				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;
				$retailPrice = $productDetailsData['productDetails'][0]['retail_price'];
				$request->session()->put('pricePurchase',$retailPrice);
				$request->session()->put('product_id', $productID);
				$request->session()->put('product_size_id', $sizeID);
				return view('website.vip-purchase', $productDetailsData);

			}

		}

	}

	// End Vip purchase


	public function purchaseBid(PurchaseBidRequest $request)
	{

		$validated = $request->validated();
		$user_id = Auth::id();
		//echo "<pre>"; print_r($validated); echo "</pre>"; die('--------------');
		
		$saveUsersShippingAddress = UsersShippingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],
														[
															'first_name' => $validated['shipping_first_name'],
															'last_name' => $validated['shipping_last_name'],
															'phone_number' => $validated['shipping_phone'],
															'province' => $validated['shipping_province'],
															'full_address' => $validated['shipping_full_address'],
															'street_city' => $validated['shipping_street_city'],
															'country' => $validated['shipping_country'],
															'zip_code' => $validated['shipping_zip'],
															'status' => 1
														]);

		if ($saveUsersShippingAddress->id) {

			$request->session()->put('shippig_address_id', $saveUsersShippingAddress->id);
			
			$saveUsersBillingAddress = UsersBillingAddressModel::updateOrCreate(
														[
															'user_id'=> $user_id,
															'default' => 1
														],
														[
															'first_name' => $validated['shipping_first_name'],
															'last_name' => $validated['shipping_last_name'],
															'phone_number' => $validated['shipping_phone'],
															'province' => $validated['shipping_province'],
															'full_address' => $validated['shipping_full_address'],
															'street_city' => $validated['shipping_street_city'],
															'country' => $validated['shipping_country'],
															'zip_code' => $validated['shipping_zip'],
															'status' => 1
														]);

			if ($saveUsersBillingAddress->id) {

				//$request->session()->put('shipping_price', 50); // this is temprory and will update after shipping integration
				$request->session()->put('billing_address_id', $saveUsersBillingAddress->id);
				return view('website.direct-payment', $validated);
			}
		}

	}


	// promo purchase

	public function purchasePromo(PurchaseBidRequest $request)
	{

		$validated = $request->validated();
		$user_id = Auth::id();
//echo "<pre>"; print_r($validated); echo "</pre>"; die('--------------');
		// $saveUsersShippingAddress = UsersShippingAddressModel::create([
		// 	'first_name' => $validated['shipping_first_name'],
		// 	'last_name' => $validated['shipping_last_name'],
		// 	'phone_number' => $validated['shipping_phone'],
		// 	'province' => $validated['shipping_province'],
		// 	'user_id' => $user_id,
		// 	'full_address' => $validated['shipping_full_address'],
		// 	'street_city' => $validated['shipping_street_city'],
		// 	'country' => $validated['shipping_country'],
		// 	'zip_code' => $validated['shipping_zip'],
		// 	'status' => 1
		// ]);

		//if ($saveUsersShippingAddress->id) {

			$shippingAddressId = $request->session()->get('shippingAddressId');
			$billingAddressId = $request->session()->get('billingAddressId');
			$request->session()->put('shippig_address_id', $shippingAddressId);

			// $saveUsersBillingAddress = UsersBillingAddressModel::create([
			// 	'first_name' => $validated['shipping_first_name'],
			// 	'last_name' => $validated['shipping_last_name'],
			// 	'phone_number' => $validated['shipping_phone'],
			// 	'province' => $validated['shipping_province'],
			// 	'user_id' => $user_id,
			// 	'full_address' => $validated['billing_full_address'],
			// 	'street_city' => $validated['billing_street_city'],
			// 	'country' => $validated['billing_country'],
			// 	'zip_code' => $validated['billing_zip'],
			// 	'status' => 1
			// ]);

			//if ($saveUsersBillingAddress->id) {

				//$request->session()->put('shipping_price', 50); // this is temprory and will update after shipping integration
				$request->session()->put('billing_address_id', $billingAddressId);
				return view('website.promo-payment', $validated);

			//}

		//}

	}

	// end promo purchase

	// Vip purchase

	public function purchaseVip(PurchaseBidRequest $request)
	{

		$validated = $request->validated();
		$user_id = Auth::id();
//echo "<pre>"; print_r($validated); echo "</pre>"; die('--------------');
		$saveUsersShippingAddress = UsersShippingAddressModel::create([
			'first_name' => $validated['shipping_first_name'],
			'last_name' => $validated['shipping_last_name'],
			'phone_number' => $validated['shipping_phone'],
			'province' => $validated['shipping_province'],
			'user_id' => $user_id,
			'full_address' => $validated['shipping_full_address'],
			'street_city' => $validated['shipping_street_city'],
			'country' => $validated['shipping_country'],
			'zip_code' => $validated['shipping_zip'],
			'status' => 1
		]);

		if ($saveUsersShippingAddress->id) {

			$request->session()->put('shippig_address_id', $saveUsersShippingAddress->id);

			$saveUsersBillingAddress = UsersBillingAddressModel::create([
				'first_name' => $validated['shipping_first_name'],
				'last_name' => $validated['shipping_last_name'],
				'phone_number' => $validated['shipping_phone'],
				'province' => $validated['shipping_province'],
				'user_id' => $user_id,
				'full_address' => $validated['billing_full_address'],
				'street_city' => $validated['billing_street_city'],
				'country' => $validated['billing_country'],
				'zip_code' => $validated['billing_zip'],
				'status' => 1
			]);

			if ($saveUsersBillingAddress->id) {

				//$request->session()->put('shipping_price', 50); // this is temprory and will update after shipping integration
				$request->session()->put('billing_address_id', $saveUsersBillingAddress->id);
				return view('website.vip-payment', $validated);

			}

		}

	}

	// End Vip purchase


	/*Sell Products directly
	* @Route direct-sell
	 * @Params
	 *@Response product detail array
	*/
	public function sellBidProduct(Request $request, $id = null, $size = null, $type = null)
	{
		$user_id = Auth::id();
		if ($id != null && $size != null) {

			$productID = base64_decode($id);

			$productDetails['productDetails'][] = ProductsModel::with([
				'productSizeTypes',
				'productSizes',
				'productsBidder',
				'productBrandType'
			])->findOrFail($productID)->toArray();

			if (is_array($productDetails) && !empty($productDetails)) {

				$bidsData = WebHelper::getProductBidsData($productID);
				$sizeID = WebHelper::getProductSizeId($size, $productID);
				$productDetailsData = $bidsData;
				$productDetailsData['productDetails']['size'] = $size;
				$productDetailsData['productDetails']['sizeID'] = $sizeID;
				$allBidsData = $productDetailsData['minSellData'];

				if (isset($allBidsData[$size])) {
					$selectedBidPrice = $allBidsData[$size];
				} else {
					$notAllowed = array();
					return view('website.not-allowed', $notAllowed);
				}

				$request->session()->put('pricePurchase', $selectedBidPrice);
				$request->session()->put('product_id', $productID);
				$request->session()->put('product_size_id', $sizeID);

				// shipping adddress
				$userShippingAddrdefault = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userShippingAddrdefault))
				{
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddrdefault;
					$request->session()->put('shippingAddressId', $userShippingAddrdefault[0]['id']);

				}else{
					$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['shippingAddress'] = $userShippingAddr;
					$request->session()->put('shippingAddressId', $userShippingAddr[0]['id']);
				}

				// return adddress
				$userReturnAddrdefault = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1,'default' => 1])->get()->toArray();
				if(!empty($userReturnAddrdefault))
				{
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddrdefault;
					$request->session()->put('billingAddressId', $userReturnAddrdefault[0]['id']);

				}else{
					$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)->where(['status' => 1])->get()->toArray();
					$productDetailsData['productDetails']['returnAddress'] = $userReturnAddr;
					$request->session()->put('billingAddressId', $userReturnAddr[0]['id']);
				}

				$productBidderId = WebHelper::getProductBidderId($productID, $sizeID, $selectedBidPrice);
				$stripeCustomerID = WebHelper::getBidderStripeCustomerID($productBidderId);
				$request->session()->put('product_bidder_id', $productBidderId);
				$request->session()->put('stripe_customer_id', $stripeCustomerID);
				//echo "<pre>"; print_r($productDetailsData); echo "</pre>";
				return view('website.direct-sell', $productDetailsData);

			}

		}

	}


	public function sellBid(SellBidRequest $request)
	{

		$validated = $request->validated();
		$user_id = Auth::id();

		// $saveUsersShippingAddress = UsersShippingAddressModel::create([
		// 	'first_name' => $validated['shipping_first_name'],
		// 	'last_name' => $validated['shipping_last_name'],
		// 	'phone_number' => $validated['shipping_phone'],
		// 	'province' => $validated['shipping_province'],
		// 	'user_id' => $user_id,
		// 	'full_address' => $validated['shipping_full_address'],
		// 	'street_city' => $validated['shipping_street_city'],
		// 	'country' => $validated['shipping_country'],
		// 	'zip_code' => $validated['shipping_zip'],
		// 	'status' => 1
		// ]);

	//	if ($saveUsersShippingAddress->id) {

			$shippingAddressId = $request->session()->get('shippingAddressId');
			$billingAddressId = $request->session()->get('billingAddressId');

			$request->session()->put('shippig_address_id', $shippingAddressId);


			// $saveUsersBillingAddress = UsersBillingAddressModel::create([
			// 	'first_name' => $validated['billing_first_name'],
			// 	'last_name' => $validated['billing_last_name'],
			// 	'phone_number' => $validated['billing_phone'],
			// 	'province' => $validated['billing_province'],
			// 	'user_id' => $user_id,
			// 	'full_address' => $validated['billing_full_address'],
			// 	'street_city' => $validated['billing_street_city'],
			// 	'country' => $validated['billing_country'],
			// 	'zip_code' => $validated['billing_zip'],
			// 	'status' => 1
			// ]);

			//if ($saveUsersBillingAddress->id) {
				//$request->session()->put('shipping_price', 50); // this is temprory and will update after shipping integration
				$request->session()->put('billing_address_id', $billingAddressId);
				return view('website.direct-sell-payment', $validated);

			//}

		//}

	}


	/* Save price to session
	* @Params rates
	* @Returns sessionValue
	*/
	public function savePriceToSession(Request $request)
	{
		$ratesArr = array();
		$ratesArr['shippingRate'] = $_GET['shippingRate'];
		$ratesArr['totalPrice'] = $_GET['totalPrice'];
		$ratesArr['processingFee'] = $_GET['processingFee'];
		$rate = $_GET['shippingRate'];

		//Store value in session
		$request->session()->put('shippingRate', $_GET['shippingRate']);
		$request->session()->put('totalPrice', $_GET['totalPrice']);
		$request->session()->put('processingFee', $_GET['processingFee']);
		$request->session()->put('commissionPrice', $_GET['commissionPrice']);

		//$value = $request->session()->get('processingFee');
		return json_encode(array('sucess'));
		die;
	}


	/*Get orders from DB and pass it to shipstation
*@Params UserName | Password | action | start_date | end_date
*@Returns order XML
*/
	/*public function shipstationOrders(Request $request)
	{
		$params = $request->all();
		//echo "<pre>"; print_r($params); echo "</pre>";
		$paramsCount = count($params);
		if ($paramsCount == 0) {
			echo "Please provide some parameters to fetch the order XML";
			return;
		}
		$username = $params['SS-UserName'];
		$password = $params['SS-Password'];
		$action = $params['action'];
		$orderPlacedStartDate = $params['start_date'];
		$orderPlacedEndDate = $params['end_date'];
		$pagination = $params['page'];

		//check if username and passwords are same as shipstation and are not empty
		if ($username != '' && $password != '') {
			//STEP I:- format the date acc to shipstation standerds
			$orderPlacedStartDate = rtrim($orderPlacedStartDate, ']'); //remove from last
			$orderPlacedStartDate = ltrim($orderPlacedStartDate, '['); //remove from beigning
			$sepStartDate = explode(' ', $orderPlacedStartDate); //break the time and year string

			$sepStartDateWithYear = $sepStartDate[0]; //seprate year section
			$sepStartDateWithTime = $sepStartDate[1]; //seprate time section
			$formatStartDate = date("Y-m-d", strtotime($sepStartDateWithYear)); //change the format
			$orderPlacedStartDate = $formatStartDate . ' ' . $sepStartDateWithTime; //concat the date
			$orderPlacedStartDate = $orderPlacedStartDate . ":00"; //append the seconds

			//STEP II:- format the end date
			$orderPlacedEndDate = rtrim($orderPlacedEndDate, ']'); //remove from last
			$orderPlacedEndDate = ltrim($orderPlacedEndDate, '['); //remove from beigning
			$sepEndDate = explode(' ', $orderPlacedEndDate); //break the time and year string

			$sepEndDateWithYear = $sepEndDate[0]; //seprate year section
			$sepEndDateWithTime = $sepEndDate[1]; //seprate time section
			$formatEndDate = date("Y-m-d", strtotime($sepEndDateWithYear)); //change the format
			$orderPlacedEndDate = $formatEndDate . ' ' . $sepEndDateWithTime; //concat the date
			$orderPlacedEndDate = $orderPlacedEndDate . ":59";

			//STEP III:- Fetch the orders with other details
			$orders = OrdersModel::with(['users', 'product', 'shipping', 'billing'])->where(['status' => 1])
				->whereBetween('created_at', array($orderPlacedStartDate, $orderPlacedEndDate))
				->orderby('id', 'desc')->get()->toArray();


			//echo "<pre>"; print_r($orders); echo "</pre>";
			//STEP IV:- Iterate the loop to make the XML file
			$orderObject = array();
			$customerObject = array();
			$testingArr = array();
			$i = 0;
			foreach ($orders as $key => $order) {
				//set the variables
				$orderId = $order['id'];
				$orderRef = $order['order_ref_number'];
				$CustomerCode = $order['users']['email'];

				//BillTo Variables
				$BillToName = $order['billing']['first_name'] . ' ' . $order['billing']['last_name'];
				$BillToPhone = $order['billing']['phone_number'];

				//ShipTo Variables
				$ShipToName = $order['shipping']['first_name'] . ' ' . $order['shipping']['last_name'];
				$ShipToAddress1 = $order['shipping']['full_address'];
				$ShipToCity = $order['shipping']['street_city'];
				$ShipToState = $order['shipping']['province'];
				$ShipToPostalCode = $order['shipping']['zip_code'];
				$ShipToCountry = $order['shipping']['country'];
				$ShipToPhone = $order['shipping']['phone_number'];

				//Product Variables
				$productName = $order['product']['product_name'];

				//order Created Date
				$orderDate = $order['created_at'];
				$testingArr['order'][$key] = $orderDate;
				$seprateDate = explode(' ', $orderDate);
				//print_r($seprateDate);
				$orderDate = date("m/d/Y", strtotime($seprateDate[0])) . ' ' . $seprateDate[1];

				//Change format acc to SHIPSTATION of Last modified date
				$lastModifiedDate = $order['updated_at'];
				$sepLastDate = explode(' ', $lastModifiedDate);
				$lastModifiedDate = date("m/d/Y", strtotime($sepLastDate[0])) . ' ' . $sepLastDate[1];

				$orderObject['Order'][$key]['OrderID'] = ["_cdata" => "hypex_$orderRef"];
				$orderObject['Order'][$key]['OrderNumber'] = ["_cdata" => "hypex_$orderRef"];
				$orderObject['Order'][$key]['OrderDate'] = $orderDate;
				$orderObject['Order'][$key]['OrderStatus'] = ["_cdata" => "paid"];
				$orderObject['Order'][$key]['LastModified'] = $lastModifiedDate;
				$orderObject['Order'][$key]['ShippingMethod'] = ["_cdata" => "xpresspost"];
				$orderObject['Order'][$key]['PaymentMethod'] = ["_cdata" => "Credit Card"];
				$orderObject['Order'][$key]['OrderTotal'] = $order['total_price'];
				//$orderObject['TaxAmount']=>$order['total_price']; not required field
				$orderObject['Order'][$key]['ShippingAmount'] = $order['shipping_price'];

				//Customer objects
				$orderObject['Order'][$key]['Customer']['CustomerCode'] = ["_cdata" => "$CustomerCode"];

				//below object contains billing related data
				$orderObject['Order'][$key]['Customer']['BillTo']['Name'] = ["_cdata" => "$BillToName"];
				$orderObject['Order'][$key]['Customer']['BillTo']['Phone'] = ["_cdata" => "$BillToPhone"];
				//$orderObject['Customer']['BillTo']['Email'] = $order['billing']['']; not a required field
				//$orderObject['Customer']['BillTo']['Company'] = $order['billing'][''];not a required field
				$orderObject['Order'][$key]['Customer']['ShipTo']['Name'] = ["_cdata" => "$ShipToName"];
				//$orderObject['Customer']['ShipTo']['Company'] = 'Trantor';
				$orderObject['Order'][$key]['Customer']['ShipTo']['Address1'] = ["_cdata" => "$ShipToAddress1"]; //required should be valid
				$orderObject['Order'][$key]['Customer']['ShipTo']['City'] = ["_cdata" => "$ShipToCity"];
				$orderObject['Order'][$key]['Customer']['ShipTo']['State'] = ["_cdata" => "$ShipToState"];
				$orderObject['Order'][$key]['Customer']['ShipTo']['PostalCode'] = ["_cdata" => "$ShipToPostalCode"];
				$orderObject['Order'][$key]['Customer']['ShipTo']['Country'] = ["_cdata" => "$ShipToCountry"];
				$orderObject['Order'][$key]['Customer']['ShipTo']['Phone'] = ["_cdata" => "$ShipToPhone"];

				//items array
				$orderObject['Order'][$key]['Items'][$i]['Item']['SKU'] = ["_cdata" => "hypex_$orderRef"];;
				$orderObject['Order'][$key]['Items'][$i]['Item']['Name'] = ["_cdata" => "$productName"];
				$orderObject['Order'][$key]['Items'][$i]['Item']['ImageUrl'] = '';
				$orderObject['Order'][$key]['Items'][$i]['Item']['Weight'] = '3';
				$orderObject['Order'][$key]['Items'][$i]['Item']['WeightUnits'] = 'Pounds';
				$orderObject['Order'][$key]['Items'][$i]['Item']['Quantity'] = '1';
				$orderObject['Order'][$key]['Items'][$i]['Item']['UnitPrice'] = $order['total_price'];
				$orderObject['Order'][$key]['Items'][$i]['Item']['Location'] = '';

				//Options Array :- will skip Options, bcoz it's not required

				$i++;
			}

			//XML code generation here
			$result = ArrayToXml::convert($orderObject, [
				'rootElementName' => 'Orders',
				'_attributes' => ['pages' => '1',],
			], true, 'UTF-8');
			//header('Content-Type: application/xml; charset=UTF-8');
			print_r($result);

		} else {
			echo "NO Access Provided";
		}

	}*/


	public function searchresult(Request $request)
	{
		//$builder = ProductsModel::query();
		$shoesData = array();
		$brand_ids = [];
        $category_ids = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
		$category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();
		
		$queryString = Input::get('search_keyword');
		$request->session()->put('search_keyword', $queryString);
		$shoesData['paginate'] = ProductsModel::with([
			'productCategory',
			'productBrandType',
			'productBrand',
			'productSizeTypes'
		])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where('product_name', 'LIKE', "%$queryString%")->where(['status' => '1'])->orderBy('updated_at',
			'DESC')->paginate();
		$productsData = $shoesData['paginate']->toArray();
		$shoesData['allProducts'] = $productsData['data'];
		//print_r($shoesData);
		//exit();
		return view('website.search-result', $shoesData);
	}

	/**
	 *  This method returns dynamic list of provinces
	 * @Params Country code
	 * @Return Province list
	 */
	public function getProvince(Request $request)
	{
		$localeSession = $request->session()->has('locale');

		//echo "test test test";
		$countryAbb  = $_POST['country_id'];
		$country = CountriesModel::select('id')->where('abbreviation',$countryAbb)->get()->first();
		$countryID = $country['id'];

		//check current session value and fetch the province acc to that

		if($request->session()->has('locale'))
		{
			$localeSession = $request->session()->get('locale');
		}else{
			$localeSession = 'en'; //by default
		}


		//get the province list acc to country code

		$provinces = ProvincesModel::select( 'id', 'name', 'abbreviation', 'status')
			->where('country_id',$countryID)
			->where('language', $localeSession)
			->get()->toArray();
		//echo "<pre>"; print_r(json_encode($provinces)); echo "<pre>";
		return json_encode($provinces);
	}

	/*This method will return the price after promo code application
	*@Params promo_code
	 * @Return discounted price
	 * */
	public function applyProductPromoCode(Request $request){
		$pass_value = '';
		if($_POST != ''){
			$promoCode = $_POST['promoCode'];
			$productID = $_POST['productID'];
			$productDetail = ProductsModel::where('id', '=', $productID)
					->where('pass_code', '=', $promoCode)
					->get(['id','pass_value', 'retail_price', 'product_images', 'release_date', 'other_product_images'])->first();
			//echo "<pre>"; print_r($productDetail); echo "</pre>";
			if($productDetail != ''){
				$pass_value = $productDetail['pass_value'];
				$request->session()->put('pass_value', $pass_value);
				return json_encode(['pass_value'=>$pass_value, 'message'=>'Promo Code Matched!', 'isFound'=>'1']);
				//echo "non empty case";
			}else{
				return json_encode(['pass_value'=>$pass_value, 'message'=>'Promo Code Does Not Match!', 'isFound'=>'0']);
			}

		}else{
			return json_encode(['pass_value'=>$pass_value, 'message'=>'No Promo Code Applied!', 'isFound'=>'0']);
		}

	}
}
