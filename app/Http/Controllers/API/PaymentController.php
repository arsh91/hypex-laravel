<?php

namespace App\Http\Controllers\API;

use App\Mail\BidSubmitted;
use App\Mail\SellSubmitted;
use App\Mail\OrderGenerated;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\APIModels\CardsModel;

use App\APIModels\BidderPayoutModel;
use App\APIModels\SellerPayoutModel;
use App\Models\OrdersModel; 
use App\APIModels\ProductsBidder;
use App\APIModels\ProductsSeller;
use App\Models\UsersShippingAddressModel;
use App\Helpers\WebHelper;
use Mail;

use App\Traits\NotificationCustomFunctions;


class PaymentController extends Controller
{
	use NotificationCustomFunctions;
    public function addCard(Request $request){
		
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripe_token'];
		$default = $tokenData['default'];
		
		try{
		
			\Stripe\Stripe::setApiKey("sk_test_XlWIEagOS9jsFlJWI0eEc2Bz00v5DAVExU");

			$customer = \Stripe\Customer::create([
				'source' => $stripeToken,
				'email' => $registredEmail,
			]);
		  
			
			$customerID = $customer->id;
			$default_source = $customer->default_source;
			$paymentEmail = $customer->email;
			$invoicePrefix = $customer->invoice_prefix;
			$stripeDetails = json_encode($customer->sources->data);
			
			if($default == 1){ CardsModel::where('user_id', $userID)->update(array('default' => 0)); }
			
			$saveDetails = CardsModel::create([
					'user_id' => $userID,
					'default_source' => $default_source,
					'stripe_customer_id' => $customerID,
					'stripe_payment_email' => $paymentEmail,
					'stripe_details' => $stripeDetails,
					'invoice_prefix' => $invoicePrefix,
					'default' => $default,
					'status' => 1
				]);
				$data['card_id'] = $saveDetails->id;
			if($saveDetails->id){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Card details saved successfully.','data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
		}catch (\Stripe\Error\RateLimit $e) {
			$message = $e->getMessage();
		} catch (\Stripe\Error\InvalidRequest $e) {
			$message = $e->getMessage();
		} catch (\Stripe\Error\Authentication $e) {
			$message = $e->getMessage();
		} catch (\Stripe\Error\ApiConnection $e) {
			$message = $e->getMessage();
		} catch (\Stripe\Error\Base $e) {
			$message = $e->getMessage();
		} catch (Exception $e) {
			$message = $e->getMessage();
		}
		return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$message ,'data'=>(object)[]]);
		
	}
	
	//paymentSellBidCase
	 public function payOfferAmount(Request $request){
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;
		try{
			\Stripe\Stripe::setApiKey("sk_test_XlWIEagOS9jsFlJWI0eEc2Bz00v5DAVExU");
			
			$data = $request->all();
			
			if(!empty($data['stripe_token'])){
			
				$stripeToken = $data['stripe_token']; 
				
				

				$customer = \Stripe\Customer::create([
					'source' => $stripeToken,
					'email' => $registredEmail,
				]);
				
				$customerID = $customer->id;
				$default_source = $customer->default_source;
				$paymentEmail = $customer->email;
				$invoicePrefix = $customer->invoice_prefix;
				$stripeDetails = json_encode($customer->sources->data);
				
				$card = current($customer->sources->data);
			}
			if(!empty($data['card_id'])){
				
				$getCard = CardsModel::where([['id','=',$data['card_id']],['user_id', '=', $userID]])->get()->first();
				 if(!empty($getCard)){
					 $customerID = $getCard['stripe_customer_id'];
					 $default_source = $getCard['default_source']; 
					 $paymentEmail = $getCard['stripe_payment_email']; 
					 $invoicePrefix = $getCard['invoice_prefix']; 
					 $stripeDetails = $getCard['stripe_details'];
					 
					 $card = current(json_decode($getCard['stripe_details']));
				 }
				
			}

			$bid = $data['bid_id']; 
			$sell_id = $data['sell_id']; 
			if(!empty($bid)){
				
				$productPrice = $data['total_price']; 
				$productPrice =  preg_replace("/[^0-9\.]/", '', $productPrice); 
				$chargeAmount = round($productPrice,2);
				
				if($chargeAmount < 100){
					$chargeAmount = 100;
				}
				//$onePercent = (1/100 * $chargeAmount);
				//$stripeAmount = $onePercent * 100; // for stripe decimal amount
				//$stripePrice = number_format((float)$onePercent, 2, '.', '');
				
				$charge = \Stripe\Charge::create([
					'amount' => round($chargeAmount),
					'currency' => 'cad',
					'customer' => $customerID,
				]);
				
				if($charge->paid == '1'){
					
					$savePayoutDetails = BidderPayoutModel::where('product_bidder_id', $bid)->update([
						'default_source' => $default_source, 
						'stripe_customer_id' => $customerID,
						'stripe_payment_email' => $paymentEmail, 
						'stripe_details' => $stripeDetails, 
						'non_refundable_amount' => ($chargeAmount/100),
						'invoice_prefix' => $invoicePrefix
					]);
				
				ProductsBidder::where('id', $bid)->update(array('status' => 1));
				$bidData = ProductsBidder::where(['id' => $bid, 'user_id' => $userID])->first()->toArray();
				$this->commonFunctionToPush('1',$bidData['product_id'],$bidData['bid_price'], $bidData['size_id'], $userID);
				$this->commonFunctionToPush('6',$bidData['product_id'],$bidData['bid_price'], $bidData['size_id'], $userID);

				
				}
				
				
				
				
			}
			
			if(!empty($sell_id)){
				
				$productPrice = $data['total_price']; 
				$productPrice =  preg_replace("/[^0-9\.]/", '', $productPrice); 
				$chargeAmount = round($productPrice,2);
				
				if($chargeAmount < 100){
					$chargeAmount = 100;
				}
				
				$charge = \Stripe\Charge::create([
					'amount' => round($chargeAmount),
					'currency' => 'cad',
					'customer' => $customerID,
				]);
				
				if($charge->paid == '1'){
				
					$savePayoutDetails = SellerPayoutModel::where('product_seller_id', $sell_id)->update([
						'default_source' => $default_source,
						'stripe_customer_id' => $customerID,
						'stripe_payment_email' => $paymentEmail,
						'stripe_details' => $stripeDetails,
						'non_refundable_amount' => ($chargeAmount/100),
						'invoice_prefix' => $invoicePrefix
					]);
					
				ProductsSeller::where('id', $sell_id)->update(array('status' => 1));
				$sellData = ProductsSeller::where(['id' => $sell_id, 'user_id' => $userID])->first()->toArray();
				$this->commonFunctionToPush('0',$sellData['product_id'],$sellData['ask_price'], $sellData['size_id'], $userID);
				$this->commonFunctionToPush('7',$sellData['product_id'],$sellData['ask_price'], $sellData['size_id'], $userID);
				$this->commonFunctionToPush('4',$sellData['product_id'],$sellData['ask_price'], $sellData['size_id'], $userID);
				$this->commonFunctionToPush('5',$sellData['product_id'],$sellData['ask_price'], $sellData['size_id'], $userID);
				}
				
			}
			
			if($savePayoutDetails == 1){
				
				if(!empty($bid)){  
					
				 /*  Mail::to($registredEmail)
					->send(new BidSubmitted($bid));*/
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Buy offer placed successfully.','data'=>(object)[]]);
				}
				
				if(!empty($sell_id)){
				   /*Mail::to($registredEmail)
					->send(new SellSubmitted($sell_id));*/
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Sell offer placed successfully.','data'=>(object)[]]);
				}
				
				
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				
			}
		}catch (\Stripe\Error\RateLimit $e) {
		  // Too many requests made to the API too quickly
			$message = $e->getMessage();
		} catch (\Stripe\Error\InvalidRequest $e) {
		  // Invalid parameters were supplied to Stripe's API
			$message = $e->getMessage();
		} catch (\Stripe\Error\Authentication $e) {
		  // Authentication with Stripe's API failed
		  // (maybe you changed API keys recently)
			$message = $e->getMessage();
		} catch (\Stripe\Error\ApiConnection $e) {
		  // Network communication with Stripe failed
			$message = $e->getMessage();
		} catch (\Stripe\Error\Base $e) {
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
			$message = $e->getMessage();
		} catch (Exception $e) {
		  // Something else happened, completely unrelated to Stripe
			$message = $e->getMessage();
		}
		return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$message ,'data'=>(object)[]]);
		
	}
	
	// If user wants to BUY now
	public function buyNow(Request $request){
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;
		try{
			\Stripe\Stripe::setApiKey("sk_test_XlWIEagOS9jsFlJWI0eEc2Bz00v5DAVExU");
			$data = $request->all();
			if(!empty($data['stripe_token'])){
			
				$stripeToken = $data['stripe_token']; 
				$customer = \Stripe\Customer::create([
					'source' => $stripeToken,
					'email' => $registredEmail,
				]);
				$customerID = $customer->id;
				$default_source = $customer->default_source;
				$paymentEmail = $customer->email;
				$invoicePrefix = $customer->invoice_prefix;
				$stripeDetails = json_encode($customer->sources->data);
				$card = current($customer->sources->data);
			}
			
			if(!empty($data['card_id'])){
				$getCard = CardsModel::where([['id','=',$data['card_id']],['user_id', '=', $userID]])->get()->first();
				 if(!empty($getCard)){
					 $customerID = $getCard['stripe_customer_id'];
					 $default_source = $getCard['default_source']; 
					 $paymentEmail = $getCard['stripe_payment_email']; 
					 $invoicePrefix = $getCard['invoice_prefix']; 
					 $stripeDetails = $getCard['stripe_details'];
					 $card = current(json_decode($getCard['stripe_details']));
				 }
			}
		
			$productPrice = $data['product_price'];
			$productPrice =  preg_replace("/[^0-9\.]/", '', $productPrice); 

			$shippingPrice = $data['shipping_rate'];
			$shippingPrice =  preg_replace("/[^0-9\.]/", '', $shippingPrice); 
			
			$totalPrice = (float)$productPrice + (float)$shippingPrice;
			$chargeAmount = round($totalPrice,2);
			$stripeAmount = $chargeAmount * 100; // for stripe decimal amount
		
			$charge = \Stripe\Charge::create([
				'amount' => $stripeAmount,
				'currency' => 'cad',
				'customer' => $customerID,
			]);
			if($charge->outcome->seller_message == 'Payment complete.'){
			
			
				$orderRefNumber  = time().mt_rand().$userID;
				$productID = $data['product_id'];
				$productSizeID = $data['product_size_id'];
				$userID = $userID;
				
				$paymentData = json_encode($charge);
				$shippingAddressId = $data['shipping_address_id'];
				$billingAddressId = $data['return_address_id'];
				$payout_email = $data['payout_email'];

				$commission_price = $data['commission_price'];
				$commission_price =  preg_replace("/[^0-9\.]/", '', $commission_price); 

				$processing_fee = $data['processing_fee'];
				$processing_fee =  preg_replace("/[^0-9\.]/", '', $processing_fee); 



				$status = '1';
				$selllerID = WebHelper::getProductSellerId($productID,$productSizeID,$productPrice);
				$sellPrimaryID = $selllerID;
				
				$savePayoutDetails = OrdersModel::create([
					'order_ref_number' => $orderRefNumber,
					'product_id' => $productID,
					'product_size_id' => $productSizeID,
					'user_id' => $userID,
					'bidder_id' => NULL,
					'seller_id' => $selllerID,
					'price' => $productPrice,
					'total_price' => $totalPrice,
					'commission_price'=> $commission_price,
					'processing_fee'=> $processing_fee,
					'shipping_price' => $shippingPrice,
					'payment_data' => $paymentData,
					'shiping_address_id' => $shippingAddressId,
					'billing_address_id' => $billingAddressId,
					'payout_email' => $payout_email,
					'status' => '1'
				]);
				
				ProductsSeller::where('id', $sellPrimaryID)->update(array('status' => 0));
				$orderID = $savePayoutDetails->id;
				Mail::to($registredEmail)
					->send(new OrderGenerated($orderID));
				$data = array();
				$data['order_id'] = $savePayoutDetails->id;
				if($savePayoutDetails->id){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Product bought successfully.','data'=>$data]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>$data]);
				}
			
			}
		}catch (\Stripe\Error\RateLimit $e) {
		  // Too many requests made to the API too quickly
			$message = $e->getMessage();
		} catch (\Stripe\Error\InvalidRequest $e) {
		  // Invalid parameters were supplied to Stripe's API
			$message = $e->getMessage();
		} catch (\Stripe\Error\Authentication $e) {
		  // Authentication with Stripe's API failed
		  // (maybe you changed API keys recently)
			$message = $e->getMessage();
		} catch (\Stripe\Error\ApiConnection $e) {
		  // Network communication with Stripe failed
			$message = $e->getMessage();
		} catch (\Stripe\Error\Base $e) {
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
			$message = $e->getMessage();
		} catch (Exception $e) {
		  // Something else happened, completely unrelated to Stripe
			$message = $e->getMessage();
		}
		return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$message ,'data'=>(object)[]]);
		
	}
	
	
	// If user wants to SELL now
	public function sellNow(Request $request){
		
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;
		
		\Stripe\Stripe::setApiKey("sk_test_XlWIEagOS9jsFlJWI0eEc2Bz00v5DAVExU");
		
		$data = $request->all();
		
		/*if(!empty($data['stripe_token'])){
		
			$stripeToken = $data['stripe_token']; 
			
			$customer = \Stripe\Customer::create([
				'source' => $stripeToken,
				'email' => $registredEmail,
			]);
			
			$customerID = $customer->id;
			$default_source = $customer->default_source;
			$paymentEmail = $customer->email;
			$invoicePrefix = $customer->invoice_prefix;
			$stripeDetails = json_encode($customer->sources->data);
			
			$card = current($customer->sources->data);
		}
		if(!empty($data['card_id'])){
			$getCard = CardsModel::where([['id','=',$data['card_id']],['user_id', '=', $userID]])->get()->first();
			 if(!empty($getCard)){
				 $customerID = $getCard['stripe_customer_id'];
				 $default_source = $getCard['default_source']; 
				 $paymentEmail = $getCard['stripe_payment_email']; 
				 $invoicePrefix = $getCard['invoice_prefix']; 
				 $stripeDetails = $getCard['stripe_details'];
				 
				 $card = current(json_decode($getCard['stripe_details']));
			 }
			
		}*/
		
		
		$productPrice = $data['product_price'];
		$productPrice =  preg_replace("/[^0-9\.]/", '', $productPrice); 
		$shippingPrice = $data['shipping_rate'];
		$shippingPrice =  preg_replace("/[^0-9\.]/", '', $shippingPrice); 
		$totalPrice = (float)$productPrice + (float)$shippingPrice;
		$chargeAmount = round($totalPrice,2);
		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount
	

		/*$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => 'cad',
			'customer' => $customerID,
		]);*/
		
        
			$orderRefNumber  = time().mt_rand().$userID;
            $productID = $data['product_id'];
            $productSizeID = $data['product_size_id'];
            $userID = $userID;
            
            
           // $paymentData = json_encode($charge);
            $shippingAddressId = $data['shipping_address_id'];
            $billingAddressId = $data['return_address_id'];
						$payout_email = $data['payout_email'];

						$commission_price = $data['commission_price'];
						$commission_price =  preg_replace("/[^0-9\.]/", '', $commission_price); 

						$processing_fee = $data['processing_fee'];
						$processing_fee =  preg_replace("/[^0-9\.]/", '', $processing_fee); 
						
            $status = '1';
						$bidderID = WebHelper::getProductBidderId($productID,$productSizeID,$productPrice);
            $bidPrimaryId = $bidderID;
            
            
            $savePayoutDetails = OrdersModel::create([
							'order_ref_number' => $orderRefNumber,
							'product_id' => $productID,
							'product_size_id' => $productSizeID,
							'user_id' => $userID,
							'seller_id' => NULL,
							'bidder_id' => $bidderID,
							'price' => $productPrice,
							'total_price' => $totalPrice,
							'commission_price'=> $commission_price,
							'processing_fee'=> $processing_fee,
							'shipping_price' => $shippingPrice,
							'payment_data' => '',
							'shiping_address_id' => $shippingAddressId,
							'billing_address_id' => $billingAddressId,
							'payout_email' => $payout_email,
							'status' => '1'
					]);
           
            ProductsBidder::where('id', $bidPrimaryId)->update(array('status' => 0));
            
            $orderID = $savePayoutDetails->id;
            Mail::to($registredEmail)
                ->send(new OrderGenerated($orderID));
			$data = array();
			$data['order_id'] = $savePayoutDetails->id;
        
            if($savePayoutDetails->id){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Product sold successfully.','data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
		
	}
	public function getProductOfferDetails(Request $request){
		$user_id = Auth::id();
		
		if(!empty($request->bid_id) || isset($request->bid_id)){
			$bid_id = $request->bid_id;
			$bidData = ProductsBidder::with(['product','size','bidderPayoutModel','billingAddress','shippingAddress'])->where(['id' => $bid_id, 'user_id' => $user_id])->first()->toArray();
		}if(!empty($request->sell_id) || isset($request->sell_id)){
			$sell_id = $request->sell_id;
			$sellData = ProductsSeller::with(['product','size','sellerPayoutModel','billingAddress','shippingAddress'])->where(['id' => $sell_id, 'user_id' => $user_id])->first()->toArray();
		}else{}
		//print_r($sellData); die;
		if(!empty($bidData)){
			$data['product_offer_detail']['bid_id'] = $bidData['id'];
			$data['product_offer_detail']['price'] = $bidData['actual_price'];
			$data['product_offer_detail']['product_offer_price'] = 'CA$'.$bidData['bid_price'];
			$date1 = strtotime($bidData['bid_expiry_date']);
			$date2 = strtotime($bidData['created_at']); 
			$diff=$date1-$date2; 
			$data['product_offer_detail']['expiry_date'] =  round($diff / 86400); 
			$data['product_offer_detail']['product_id'] = $bidData['product']['id'];
			$data['product_offer_detail']['product_name'] = $bidData['product']['product_name'];
			$images = $bidData['product']['product_images'];
			$file = $images;
			$prodImages = explode(',',$file);
			$data['product_offer_detail']['product_images'] = url('/').'/'.current($prodImages);
			//$data['product_offer_detail']['product_name'] = $bidData['product']['retail_price'];
			$data['product_offer_detail']['product_size_id'] = $bidData['size']['id'];
			$data['product_offer_detail']['size'] = $bidData['size']['size'];
			$data['payout_address'] = isset($bidData['bidder_payout_model']['paypal_id']) ? $bidData['bidder_payout_model']['paypal_id'] : '';
			
			//$data['total_price '] = $bidData['bid_order']['total_price'];
			//$data['shipping_price '] = $bidData['bid_order']['shipping_price'];
			$data['return_address'] = $bidData['billing_address'];
			$data['shipping_address'] = $bidData['shipping_address'];
			$data['offer_details'] = $this->offerDetails($bidData['bid_price'], $bidData['shipping_address']['id'], 1, 0);
			
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Offer data retrieved successfully.', 'data'=>$data]);
		}
		else if(!empty($sellData)){
			$data['product_offer_detail']['sell_id'] = $sellData['id'];
			$data['product_offer_detail']['price'] = $sellData['actual_price'];
			$data['product_offer_detail']['product_offer_price'] = 'CA$'.$sellData['ask_price'];
			//$data['product_offer_detail']['date'] = $sellData['sell_expiry_date'];
			$date1 = strtotime($sellData['sell_expiry_date']);
			$date2 = strtotime($sellData['created_at']); 
			$diff=$date1-$date2; 
			$data['product_offer_detail']['expiry_date'] =  round($diff / 86400); 
			$data['product_offer_detail']['product_id'] = $sellData['product']['id'];
			$data['product_offer_detail']['product_name'] = $sellData['product']['product_name'];
			$images = $sellData['product']['product_images'];
			$file = $images;
			$prodImages = explode(',',$file);
			$data['product_offer_detail']['product_images'] = url('/').'/'.current($prodImages);
			//$data['product_offer_detail']['product_name'] = $sellData['product']['retail_price'];
			$data['product_offer_detail']['product_size_id'] = $sellData['size']['id'];
			$data['product_offer_detail']['size'] = $sellData['size']['size'];
			$data['payout_address'] = $sellData['seller_payout_model']['paypal_id'];
			//$data['total_price '] = $sellData['sell_order']['total_price'];
			//$data['shipping_price '] = $sellData['sell_order']['shipping_price'];
			$data['return_address'] = $sellData['billing_address'];
			$data['shipping_address'] = $sellData['shipping_address'];
			$data['offer_details'] = $this->offerDetails($sellData['ask_price'], $sellData['shipping_address']['id'], 1, 1);
			
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Offer data retrieved successfully.', 'data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data with this offer.', 'data'=>$data]);
			
		}
			
	}
	
	/* *get Shipment details and price 
		is_buying_selling_now (now = 1 or offer = 0) */
	
	public function getOfferDetails(Request $request){
		
		$fixedCadAmount = '01.00'; //Canadian Dollar
		$percen = 3;
		$commissionPrice = '1';
		
		$data = $request->all();
		$product_price = $data['product_price'];
		$shipping_id = $data['shipping_address_id'];
		$offer_type = $data['is_offer'];
		$is_sell = $data['is_sell'];
		
		$price =  preg_replace("/[^0-9\.]/", '', $product_price); 
		
		$getShippingAddresses = UsersShippingAddressModel::select('country','zip_code')->where('id',$shipping_id)->get()->first();
		$country = $getShippingAddresses['country']; 
		$zip = $getShippingAddresses['zip_code']; 
		
			$ch = curl_init();
			//curl_setopt($ch, CURLOPT_URL, "https://ssapi.shipstation.com/shipments/getrates");
			curl_setopt($ch, CURLOPT_URL, "https://private-anon-e9bf51dc6a-shipstation.apiary-mock.com/shipments/getrates");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			  \"carrierCode\": \"canada_post\",
			  \"serviceCode\": \"xpresspost\",
			  \"packageCode\": null,
			  \"fromPostalCode\": \"V6V 1Z4\",
			  \"toState\": \"NU\",
			  \"toCountry\": \"$country\",
			  \"toPostalCode\": \"$zip\",
			  \"toCity\": \"ARCTIC BAY\",
			  \"weight\": {
				\"value\": 3,
				\"units\": \"pounds\"
			  },
			  \"dimensions\": {
				\"units\": \"centimeters\",
				\"length\": 35.00,
				\"width\": 23.50,
				\"height\": 13.50
			  },
			  \"confirmation\": \"delivery\",
			  \"residential\": false
			}");
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  "Content-Type: application/json",
			  "Authorization: Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM="
			));
			
			$response = curl_exec($ch);
			
			curl_close($ch);
			$res = json_decode($response);
			if(isset($res->ExceptionMessage) && !empty($res->ExceptionMessage)){
				if($res->ExceptionMessage == 'One or more providers reported an error'){
					
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Update your shipping address with the correct zip code.','data'=>(object)[]]);
				}
				elseif($res->ExceptionMessage == 'Invalid ToCountry. Please use the two-character ISO country code'){
					
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Update your shipping address with the correct Country.','data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some error occured.','data'=>(object)[]]);
				}
			}else{
				
				$shipmentCost = $res[0]->shipmentCost;
				$otherCost = $res[0]->otherCost;
		
				
				
				if($offer_type == 0){
					if($is_sell == 1){
						// price calculations (sellNow case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$price - (float)$shipRateWithCAD;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						$finalCalPrice = $finalCalPrice - $comPrice;
						$finalCalPrice = round($finalCalPrice,2); //wrap upto 2 float 
						
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($finalCalPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['payable_fee'] = 'CA$'.$finalCalPrice; }
						
					}else{
						
						// price calculations (buyNow case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$shipRateWithCAD + (float)$price;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						$finalCalPrice = (float)$sellPriceShipRateWithCAD + (float)$processingFee;
						
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						$finalCalPrice = $finalCalPrice - $comPrice;
						$finalCalPrice = round($finalCalPrice,2); //wrap upto 2 float 
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($finalCalPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['payable_fee'] = 'CA$'.$finalCalPrice; }
						
					}
				}else{
					if($is_sell == 1){
						// price calculations (Selloffer case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$price - (float)$shipRateWithCAD;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						$finalCalPrice = round($finalCalPrice,2); 
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($comPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($comPrice); }else{ $data['payable_fee'] = 'CA$'.$comPrice; }
					
					}else{
						// price calculations (Buyoffer case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$shipRateWithCAD + (float)$price;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD + (float)$processingFee;
						$finalCalPrice = round($finalCalPrice,2); 
						
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($comPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($comPrice); }else{ $data['payable_fee'] = 'CA$'.$comPrice; }
						
					}
				}
				
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Offer details retrieved successfully.','data'=>$data]);
		}
	}
	
	public function offerDetails($product_price, $shipping_id, $offer_type, $is_sell){
		$fixedCadAmount = '01.00'; //Canadian Dollar
		$percen = 3;
		$commissionPrice = '1';
		
		$product_price = $product_price;
		$shipping_id = $shipping_id;
		$offer_type = $offer_type;
		$is_sell = $is_sell;
		
		$price =  preg_replace("/[^0-9\.]/", '', $product_price); 
		
		$getShippingAddresses = UsersShippingAddressModel::select('country','zip_code')->where('id',$shipping_id)->get()->first();
		$country = $getShippingAddresses['country']; 
		$zip = $getShippingAddresses['zip_code']; 
		
			$ch = curl_init();
			//curl_setopt($ch, CURLOPT_URL, "https://ssapi.shipstation.com/shipments/getrates");
			curl_setopt($ch, CURLOPT_URL, "https://private-anon-e9bf51dc6a-shipstation.apiary-mock.com/shipments/getrates");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			  \"carrierCode\": \"canada_post\",
			  \"serviceCode\": \"xpresspost\",
			  \"packageCode\": null,
			  \"fromPostalCode\": \"V6V 1Z4\",
			  \"toState\": \"NU\",
			  \"toCountry\": \"$country\",
			  \"toPostalCode\": \"$zip\",
			  \"toCity\": \"ARCTIC BAY\",
			  \"weight\": {
				\"value\": 3,
				\"units\": \"pounds\"
			  },
			  \"dimensions\": {
				\"units\": \"centimeters\",
				\"length\": 35.00,
				\"width\": 23.50,
				\"height\": 13.50
			  },
			  \"confirmation\": \"delivery\",
			  \"residential\": false
			}");
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  "Content-Type: application/json",
			  "Authorization: Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM="
			));
			
			$response = curl_exec($ch);
			
			curl_close($ch);
			$res = json_decode($response);
			if(isset($res->ExceptionMessage) && !empty($res->ExceptionMessage)){
				if($res->ExceptionMessage == 'One or more providers reported an error'){
					
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Update your shipping address with the correct zip code.','data'=>(object)[]]);
				}
				elseif($res->ExceptionMessage == 'Invalid ToCountry. Please use the two-character ISO country code'){
					
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Update your shipping address with the correct Country.','data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some error occured.','data'=>(object)[]]);
				}
			}else{
				
				$shipmentCost = $res[0]->shipmentCost;
				$otherCost = $res[0]->otherCost;
		
				
				
				if($offer_type == 0){
					if($is_sell == 1){
						// price calculations (sellNow case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$price - (float)$shipRateWithCAD;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						$finalCalPrice = $finalCalPrice - $comPrice;
						$finalCalPrice = round($finalCalPrice,2); //wrap upto 2 float 
						
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($finalCalPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['payable_fee'] = 'CA$'.$finalCalPrice; }
						
					}else{
						
						// price calculations (buyNow case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$shipRateWithCAD + (float)$price;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						$finalCalPrice = (float)$sellPriceShipRateWithCAD + (float)$processingFee;
						
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						$finalCalPrice = $finalCalPrice - $comPrice;
						$finalCalPrice = round($finalCalPrice,2); //wrap upto 2 float 
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($finalCalPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['payable_fee'] = 'CA$'.$finalCalPrice; }
						
					}
				}else{
					if($is_sell == 1){
						// price calculations (Selloffer case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$price - (float)$shipRateWithCAD;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD - (float)$processingFee;
						$finalCalPrice = round($finalCalPrice,2); 
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($comPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($comPrice); }else{ $data['payable_fee'] = 'CA$'.$comPrice; }
					
					}else{
						// price calculations (Buyoffer case)
						$shipmentCostWithDollar = 'CA$'.$shipmentCost;
						$shipRateWithCAD = (float)$shipmentCost + (float)$fixedCadAmount;
						$sellPriceShipRateWithCAD = (float)$shipRateWithCAD + (float)$price;
						
						if (empty($sellPriceShipRateWithCAD) || empty($percen)) {
                            $processingFee = " ";
                        } else {
                            $processingFee = round((($percen * $sellPriceShipRateWithCAD) / 100),2);
                        }
						
						$finalCalPrice = (float)$sellPriceShipRateWithCAD + (float)$processingFee;
						$finalCalPrice = round($finalCalPrice,2); 
						
						$comPrice = ((float)$finalCalPrice/100)*$commissionPrice;
						$comPrice = round($comPrice,2);
						if($comPrice < 1){
							 $comPrice = '1.00';
						}
						
						
						
						$data = array();
						$data['shipping_fee'] =  $shipmentCostWithDollar;
						if($processingFee < 0 ){ $data['processing_fee'] =  '-CA$'.abs($processingFee); }else{ $data['processing_fee'] =  'CA$'.$processingFee; }
						if($comPrice < 0 ){ $data['transaction_fee']  = '-CA$'.abs($comPrice); }else{ $data['transaction_fee']  = 'CA$'.$comPrice; }
						if($finalCalPrice < 0 ){ $data['total_fee'] = '-CA$'.abs($finalCalPrice); }else{ $data['total_fee'] = 'CA$'.$finalCalPrice; }
						if($comPrice < 0 ){ $data['payable_fee'] = '-CA$'.abs($comPrice); }else{ $data['payable_fee'] = 'CA$'.$comPrice; }
						
					}
				}
				
				return $data;
		}
		
	}
}
