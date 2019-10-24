<?php
namespace App\Helpers;

use App\Models\ProductsModel;
use App\Models\BrandsModel;
use App\Models\ProductSizeTypes;
use App\Models\ProductsSeller;
use App\Models\ProductsBidder;
use App\Models\CurrencyConversionModel;
use App\Models\BidderPayoutModel;
use App\APIModels\OrdersModel;
use Currency;
use Session;

use App\User;

class WebHelper
{

	public static function getProductBidsData($productID)
	{
		//GET THE CURRENCY CODE IN SESSION
		$session_currency_code = '';
		if (Session::has('currencyCode')) {
			$session_currency_code = Session::get('currencyCode');
		} else {
			$session_currency_code = 'USD';
		}

		$productDetails['productDetails'][] = ProductsModel::with([
			'productSizeTypes',
			'productSizes',
			'productsBidder',
			'productBrandType',
			'productBrand',
			'productsSeller'
		])->findOrFail($productID)->toArray();

		$prodDetails = $productDetails['productDetails'];
		$splitData = current($prodDetails);
		$prodImages = $splitData['product_image_link'];
		$bidData = $splitData['products_bidder'];
		$sellData = $splitData['products_seller'];
		$prodSizeData = $splitData['product_sizes'];
		$finalBidData = array();
		$finalSellData = array();

		// getting All sizes and its primaryID
		foreach ($prodSizeData as $key => $value) {
			$sizeArray[$value['id']] = $value['size'];
		}

		// Merging Primary IDs for Bid
		// Merging Primary
		if (!empty($bidData)) {
			foreach ($bidData as $key => $value) {

				$expiryDate = $value['bid_expiry_date'];
				$todayDate = date('Y-m-d h:i:s');
				if ($expiryDate < $todayDate) {
					continue;
				}
				$size = $sizeArray[$value['size_id']];
				$finalBidData[$size][] = $value['bid_price'];
				/*if($value['currency_code'] != $session_currency_code){
					$finalBidData[$size][] = WebHelper::changePriceWithoutCurrency($session_currency_code, $value['currency_code'], $value['bid_price']);
				}else{
					$finalBidData[$size][] = $value['bid_price'];
				}*/
			}
		} else {
			$finalBidData = array();
		}

		// Merging Primary IDs for Sell
		if (!empty($sellData)) {
			foreach ($sellData as $key => $value) {

				$expiryDate = $value['sell_expiry_date'];
				$todayDate = date('Y-m-d h:i:s');
				if ($expiryDate < $todayDate) {
					continue;
				}
				$size = $sizeArray[$value['size_id']];
				$finalSellData[$size][] = $value['ask_price'];
				/*if($value['currency_code'] != $session_currency_code){
					$finalSellData[$size][] = WebHelper::changePriceWithoutCurrency($session_currency_code, $value['currency_code'], $value['ask_price']);
				}else{
					$finalSellData[$size][] = $value['ask_price'];
				}*/
			}
		} else {
			$finalSellData = array();
		}

		// fetching Max Bid from all the Bid Data
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

		$productDetails['product_image_link'] = $prodImages;
		$productDetails['maxBidsData'] = $minSellData;
		$productDetails['minSellData'] = $maxBidsData;


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

		return $productDetails;

	}

	//  product promo 

	public static function getProductPromoData($productID)
	{
		//GET THE CURRENCY CODE IN SESSION
		$session_currency_code = '';
		if (Session::has('currencyCode')) {
			$session_currency_code = Session::get('currencyCode');
		} else {
			$session_currency_code = 'USD';
		}

		$productDetails['productDetails'][] = ProductsModel::with([
			'productSizeTypes',
			'productSizes',
			'productBrandType',
			'productBrand'
		])->findOrFail($productID)->toArray();
		$prodDetails = $productDetails['productDetails'];
		$splitData = current($prodDetails);
		$prodImages = $splitData['product_image_link'];
		$prodSizeData = $splitData['product_sizes'];
		$finalBidData = array();
		$finalSellData = array();

		// getting All sizes and its primaryID
		foreach ($prodSizeData as $key => $value) {
			$sizeArray[$value['id']] = $value['size'];
		}

		$productDetails['product_image_link'] = $prodImages;

		return $productDetails;

	}


	public static function getProductSizeId($size, $productID)
	{

		$productDetails = ProductsModel::with(['productSizeTypes', 'productSizes'])->findOrFail($productID)->toArray();
		$productAllSizes = $productDetails['product_sizes'];
		foreach ($productAllSizes as $key => $value) {

			if ($value['size'] == $size) {
				return $value['id'];
			}

		}

	}

	public static function getProductSellerId($productID, $sizeID, $selectedBidPrice)
	{

		$productSellerData = ProductsSeller::where([
			'product_id' => $productID,
			'size_id' => $sizeID,
			'ask_price' => $selectedBidPrice,
			'status' => 1
		])->first();

		if (isset($productSellerData->id)) {
			$productSellerID = $productSellerData->id;
			return $productSellerID;
		}

	}


	public static function getProductBidderId($productID, $sizeID, $selectedSellPrice)
	{

		$productBidderData = ProductsBidder::where([
			'product_id' => $productID,
			'size_id' => $sizeID,
			'bid_price' => $selectedSellPrice,
			'status' => 1
		])->first();

		if (isset($productBidderData->id)) {
			$productBidderID = $productBidderData->id;
			return $productBidderID;
		}

	}


	public static function getBidderStripeCustomerID($prodBidderID)
	{

		$bidderPayoutDetails = BidderPayoutModel::where(['product_bidder_id' => $prodBidderID])->first();
		if (isset($bidderPayoutDetails->id)) {
			$stripeCustomerId = $bidderPayoutDetails->stripe_customer_id;
			return $stripeCustomerId;
		}
	}


	/***** functions added by Sapna *****/
	public static function getLastSalePrice($product_id)
	{

		$orderDetails = OrdersModel::where('product_id', $product_id)->orderBy('id', 'desc')->get()->first();
		$orders = array();
		if (!empty($orderDetails)) {
			$orders['price'] = 'CA$' . $orderDetails['price'];

			$orders['size'] = WebHelper::getSize($orderDetails['product_size_id'], $product_id);
		} else {
			$orders['price'] = '';
			$orders['size'] = '';
		}
		return $orders;


	}

	public static function getSize($size_id, $productID)
	{
		$productDetails = ProductsModel::with(['productSizeTypes', 'productSizes'])->findOrFail($productID)->toArray();
		$productAllSizes = $productDetails['product_sizes'];
		foreach ($productAllSizes as $key => $value) {

			if ($value['id'] == $size_id) {
				$size = $value['size'];
			}

		}
		if (!empty($size)) {
			return $size;
		} else {
			return '';
		}


	}

	public static function getBrandNameById($id)
	{
		$brands = BrandsModel::where("id", $id)->get()->first();
		return $brands['brand_name'];
	}

	public static function getUserName($user_id)
	{

		$userDetails = user::select('first_name', 'last_name')->findOrFail($user_id)->toArray();
		return $userDetails['full_name'];

	}

	/*This will change the currency acc to the currency code in the session
	@Params currency_code
	@Return Rate | numeric value*/
	public static function changePriceWithoutCurrency($newCurrency, $oldCurrency, $currencyAmount)
	{
		/*echo $currencyAmount;
		echo $newCurrency;
		echo $oldCurrency;*/
		$newPrice = '';
		if ($newCurrency != $oldCurrency) { //USD != CAD change the price
			//echo "both are diff";
			$convertResponse = Currency::convert($oldCurrency, $newCurrency, $currencyAmount);
			//print_r($convertResponse); die('-----------');
			//$newPrice = $convertResponse['convertedAmount'];

		} else { //eg USD == USD then display the old price
			$newPrice = $currencyAmount;
		}
		return $newPrice;
	}

	/*Change currency rate acc to admin data
	* Default Currency = CAD
	 *  */
	public static function currencyConversion($newCurrency, $oldCurrency, $currencyAmount)
	{
		$conversionRate = 1; //by default CAD and conversion rate = 1
		$calAmount = $currencyAmount;
		$baseCurrency = 'CAD'; //base currency
		if ($newCurrency != $oldCurrency) { //CAD != USD change the price
			//echo "old =".$oldCurrency."-----new =".$newCurrency;
			//CASE I :- USD, get the currency rate from DB table `currency_conversion`
			if ($newCurrency == 'USD') {
				//echo "<br>change it into Us dollar<br>";
				$currRate = CurrencyConversionModel::where('currency_code', $newCurrency)->orderBy('id',
					'desc')->get()->first();
				if (!empty($currRate)) {
					$conversionRate = $currRate['conversion_rate'];
					if ($oldCurrency == $baseCurrency) { // FROM CAD to USD
						$calAmount = round(($currencyAmount * $conversionRate), 2);
					}
				}
			} elseif ($newCurrency == 'CNY') { //FROM CAD TO CYN
				//echo "<br>change it into chinese<br>";
				$currRate = CurrencyConversionModel::where('currency_code', $newCurrency)->orderBy('id',
					'desc')->get()->first();
				if (!empty($currRate)) {
					$conversionRate = $currRate['conversion_rate'];
					if ($oldCurrency == $baseCurrency) { // FROM CAD to USD
						$calAmount = round(($currencyAmount * $conversionRate), 2);
					}

				}

			} else {
				$calAmount = $currencyAmount;

			}

		}else{
			//echo "CAD->CAD";
			$calAmount = $currencyAmount;
		}
		return $calAmount;
	}

}