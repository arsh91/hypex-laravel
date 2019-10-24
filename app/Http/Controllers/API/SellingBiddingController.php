<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseBidRequest;
use App\Http\Requests\SellBidRequest;

use App\Http\Controllers\Controller;

use App\APIModels\ProductsModel;
use App\APIModels\BrandsModel;
use App\APIModels\ProductSizeTypes;
use App\APIModels\ProductsBidder;
use App\Models\ProductsSeller;
use App\Models\BidderPayoutModel;
use App\Models\SellerPayoutModel;
use App\Models\OrdersModel; 

use App\Helpers\WebHelper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;



use Carbon\Carbon;

class SellingBiddingController extends Controller
{
	
	public function buyOffer(Request $request){
		$user_id = Auth::id();  dd($user_id);
		$productID= $request->product_id;
		$size_id= $request->product_size_id;
		$payout_email = $request->payout_email;

		$shipping_price = $request->shipping_rate;
		$shipping_price = preg_replace("/[^0-9\.]/", '', $shipping_price); 
		$shipping_price = round($shipping_price,2);

		$total_price = $request->total_price;
		$total_price =  preg_replace("/[^0-9\.]/", '', $total_price); 
		$total_price = round($total_price,2);

		$commission_price = $request->commission_price;
		$commission_price =  preg_replace("/[^0-9\.]/", '', $commission_price); 
		$commission_price = round($commission_price,2);

		$processing_fee = $request->processing_fee;
		$processing_fee =  preg_replace("/[^0-9\.]/", '', $processing_fee); 
		$processing_fee = round($processing_fee,2);
		
		$product_price = $request->product_price; 
		$product_price =  preg_replace("/[^0-9\.]/", '', $product_price); 
		$product_price = round($product_price,2);
		
		$bid_price = $request->bid_price; 
		$bid_price =  preg_replace("/[^0-9\.]/", '', $bid_price); 
		$bid_price = round($bid_price,2);
		
		if($productID != NULL && $size_id != NULL){

			$bidDays = 15;
				
				$bidDays = $request->bid_expiry_date;
				
				$expiryDate = Carbon::now()->addDays($bidDays);
		
				$bidderPrimaryID = ProductsBidder::create([ 
					'user_id' => $user_id,
					'product_id' => $productID,
					'size_id' => $size_id,
					'actual_price' => $product_price,
					'bid_price' => $bid_price,
					'shipping_price' => $shipping_price,
					'total_price' => $total_price,
					'commission_price'=> $commission_price,
					'processing_fee'=> $processing_fee,
					'billing_address_id' => $request->return_address_id,
					'shiping_address_id' => $request->shipping_address_id,
					'bid_expiry_date' => $expiryDate,
					'status' => 0
				]); 
			$data['bid_id'] = $bidderPrimaryID->id;
			
			if(!empty($bidderPrimaryID->id) && !empty($payout_email)){
				$savePayoutDetails = BidderPayoutModel::create([
					'product_bidder_id' => $bidderPrimaryID->id,
					'default_source' => "",
					'stripe_customer_id' => "",
					'stripe_payment_email' => "",
					'non_refundable_amount'=>"",
					'stripe_details' => "",
					'paypal_id' => $payout_email,
					'invoice_prefix' => ""
				]);
			}
			if(!empty($data)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Buy offer placed, payment pending.', 'data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
		}
		
	}
	
	public function sellOffer(Request $request){ 
		$user_id = Auth::id();  
		$productID= $request->product_id;
		$size_id= $request->product_size_id;
		$payout_email = $request->payout_email;

		$shipping_price = $request->shipping_rate;
		$shipping_price =  preg_replace("/[^0-9\.]/", '', $shipping_price); 
		$shipping_price = round($shipping_price,2);
		
		$total_price = $request->total_price;
		$total_price =  preg_replace("/[^0-9\.]/", '', $total_price); 
		$total_price = round($total_price,2);

		$commission_price = $request->commission_price;
		$commission_price =  preg_replace("/[^0-9\.]/", '', $commission_price); 
		$commission_price = round($commission_price,2);

		$processing_fee = $request->processing_fee;
		$processing_fee =  preg_replace("/[^0-9\.]/", '', $processing_fee); 
		$processing_fee = round($processing_fee,2);
		
		$product_price = $request->product_price; 
		$product_price =  preg_replace("/[^0-9\.]/", '', $product_price); 
		$product_price = round($product_price,2);
		
		$ask_price = $request->ask_price; 
		$ask_price =  preg_replace("/[^0-9\.]/", '', $ask_price); 
		$ask_price = round($ask_price,2);
		
		if($productID != NULL && $size_id != NULL){

			$bidDays = 15;
				
				$bidDays = $request->bid_expiry_date;
				
				$expiryDate = Carbon::now()->addDays($bidDays);
		//dd($size_id);
				$sellerPrimaryID = ProductsSeller::create([
					'user_id' => $user_id,
					'product_id' => $productID, 
					'size_id' => $size_id,
					'actual_price' =>$product_price,
					'ask_price' => $ask_price,
					'shipping_price' => $shipping_price,
					'total_price' => $total_price,
					'commission_price'=> $commission_price,
					'processing_fee'=> $processing_fee,
					'billing_address_id' => $request->return_address_id,
					'shiping_address_id' => $request->shipping_address_id,
					'sell_expiry_date' => $expiryDate,
					'status' => 0
				]);
			$data['sell_id'] = $sellerPrimaryID->id;
			
			if(!empty($sellerPrimaryID->id) && !empty($payout_email)){
				
				$savePayoutDetails = SellerPayoutModel::create([
					'product_seller_id' => $sellerPrimaryID->id,
					'default_source' => "",
					'stripe_customer_id' => "",
					'stripe_payment_email' => "",
					'stripe_details' => "",
					'non_refundable_amount'=>"",
					'paypal_id' => $payout_email,
					'invoice_prefix' => ""
				]);
			} 
			if(!empty($data)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Sell offer placed, payment pending.', 'data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
		}
		
	}
	
	/*** function to edit open offers only ****/
	
	public function editOffer(Request $request){
		$user_id = Auth::id();
		$productID= $request->product_id;
		$sell_id= $request->sell_id; 
		$bid_id= $request->bid_id; 
		if($productID != NULL && $sell_id != NULL){

				$bidDays = $request->bid_expiry_date;
				
				$expiryDate = Carbon::now()->addDays($bidDays);
				
				$saveSellerDetails = ProductsSeller::where('id', $sell_id)->update([
								'ask_price' => $request->ask_price,
								'billing_address_id' => $request->return_address_id,
								'shiping_address_id' => $request->shipping_address_id,
								'sell_expiry_date' => $expiryDate
							]);
		
				$savePayoutDetails = SellerPayoutModel::where('product_seller_id', $sell_id)->update([
								'paypal_id' => $request->payout_email
							]);
				if($saveSellerDetails){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Sell Offer updated successfully.', 'data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
		}else if($productID != NULL && $bid_id != NULL){
				$bidDays = $request->bid_expiry_date;
				
				$expiryDate = Carbon::now()->addDays($bidDays);
				
				$saveSellerDetails = ProductsBidder::where('id', $bid_id)->update([
								'bid_price' => $request->bid_price,
								'billing_address_id' => $request->return_address_id,
								'shiping_address_id' => $request->shipping_address_id,
								'bid_expiry_date' => $expiryDate
							]);
		
				$savePayoutDetails = BidderPayoutModel::where('product_bidder_id', $bid_id)->update([
								'paypal_id' => $request->payout_email
							]);
				if($saveSellerDetails){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Bid Offer updated successfully.', 'data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
		}else{
			
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Provide valid inputs.','data'=>(object)[]]);
		}
		
	}
		
	
	
	
}
