<?php

namespace App\Http\Controllers\v1\website;

use App\Mail\BidSubmitted;
use App\Mail\SellSubmitted;
use App\Mail\OrderGenerated;
use App\Mail\BuyNowforSeller;
use App\Mail\SellNowforBuyer;
use App\Mail\BuySubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\BidderPayoutModel;
use App\Models\SubscriptionPlan;
use App\Models\SellerPayoutModel;
use App\Models\OrdersModel;
use App\Models\ProductsBidder;
use App\Models\SubscriptionsModel;
use App\Models\SubscriptionPayoutModel;
use App\Models\ProductsSeller;
use App\Models\CardsModel;
use App\User;
use Mail;


class PaymentController extends Controller
{
	public function createStripeCustomer(Request $request)
	{

		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];

		$type = $request->session()->get('type');

		$chargeCurrency = '';
		if ($request->session()->has('currencyCode')) {
			$chargeCurrency = $request->session()->get('currencyCode'); // get the dynamic currency from session
		} else {
			$chargeCurrency = 'CAD';
		}

		$primaryID = $request->session()->get('primary');

		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
         //    \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");


		$customer = \Stripe\Customer::create([
			'source' => $stripeToken,
			'email' => $registredEmail,
		]);


		$customerID = $customer->id;
		$default_source = $customer->default_source;
		$paymentEmail = $customer->email;
		$invoicePrefix = $customer->invoice_prefix;
		$stripeDetails = json_encode($customer->sources->data);


		if ($type == 'bid') {
			//echo "<pre>"; print_r($request); echo "</pre>"; die;
			$chargeAmount = $request->session()->get('totalPrice');

			if ($chargeAmount < 100) {
				$chargeAmount = 100;
			}
			//$onePercent = (1/100 * $chargeAmount);
			//$stripeAmount = $onePercent * 100; // for stripe decimal amount
			//$stripePrice = number_format((float)$onePercent, 2, '.', '');

			$charge = \Stripe\Charge::create([
				'amount' => round($chargeAmount),
				'currency' => $chargeCurrency, // now currency will be dynamic
				'customer' => $customerID,
			]);

			if ($charge->paid == '1') {

				$savePayoutDetails = BidderPayoutModel::create([
					'product_bidder_id' => $primaryID,
					'default_source' => $default_source,
					'stripe_customer_id' => $customerID,
					'stripe_payment_email' => $paymentEmail,
					'stripe_details' => $stripeDetails,
					//'paypal_id' => $paypal_id,
					'paypal_id' => '',
					'non_refundable_amount' => ($chargeAmount / 100),
					'invoice_prefix' => $invoicePrefix
				]);

				$bidderPrimaryID = $request->session()->get('bidPrimaryId');
				ProductsBidder::where('id', $bidderPrimaryID)->update(array('status' => 1));

			}


		}

		if ($type == 'sell') {
			
			$paypal_id = $tokenData['paypal_id'];
			
			$chargeAmount = $request->session()->get('totalPrice');

			if ($chargeAmount < 100) {
				$chargeAmount = 100;
			}

			$charge = \Stripe\Charge::create([
				'amount' => round($chargeAmount),
				'currency' => $chargeCurrency,
				'customer' => $customerID,
			]);

			if ($charge->paid == '1') {

				$savePayoutDetails = SellerPayoutModel::create([
					'product_seller_id' => $primaryID,
					'default_source' => $default_source,
					'stripe_customer_id' => $customerID,
					'stripe_payment_email' => $paymentEmail,
					'stripe_details' => $stripeDetails,
					'paypal_id' => $paypal_id,
					'non_refundable_amount' => ($chargeAmount / 100),
					'invoice_prefix' => $invoicePrefix
				]);

				$sellPrimaryID = $request->session()->get('sellPrimaryId');
				ProductsSeller::where('id', $sellPrimaryID)->update(array('status' => 1));
			}

		}


		if ($savePayoutDetails->id) {

			if ($type == 'bid') {

				Mail::to($registredEmail)
					->send(new BidSubmitted($bidderPrimaryID));
				$bidderpayoutdetails = $savePayoutDetails->toArray();
				$productbidderdetails = ProductsBidder::with([
					'users',
					'product',
					'size'
				])->where(['id' => $bidderpayoutdetails['product_bidder_id']])->first()->toArray();
				$data = array('type' => $type);
				return view('website.bid-detail', compact('data', 'productbidderdetails'));
			}

			if ($type == 'sell') {
				Mail::to($registredEmail)
					->send(new SellSubmitted($sellPrimaryID));
				$sellerpayoutdetails = $savePayoutDetails->toArray();
				$productsellerdetails = ProductsSeller::with([
					'product',
					'size'
				])->where(['id' => $sellerpayoutdetails['product_seller_id']])->first()->toArray();
				$data = array('type' => $type);
				return view('website.sell-detail', compact('data', 'productsellerdetails'));
			}
		} else {

			$data = array('type' => $type);
			return view('website.error', $data);
		}

	}

	// If user wants to BUY now[DIRECT BUY]
	public function chargePayment(Request $request)
	{
		//echo "<pre>"; print_r($request->session()->all()); echo "</pre>"; die('heerererer');
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];
		$paypal_id = $tokenData['paypal_id'];
		
		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
         //     \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");



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

		$chargeAmount = $request->session()->get('totalPrice');
		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount

		$currencyCode = '';
		if ($request->session()->has('currencyCode')) {
			$currencyCode = $request->session()->get('currencyCode'); // get the dynamic currency from session
		} else {
			$currencyCode = 'CAD'; //if no session set then use default one
		}

		$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => $currencyCode,
			'customer' => $customerID,
		]);

		if ($charge->outcome->seller_message == 'Payment complete.') {


			$data = $request->session()->all();

			$orderRefNumber = time() . mt_rand() . $userID;
			$productID = $data['product_id'];
			$productSizeID = $data['product_size_id'];
			$userID = $userID;
			$selllerID = $data['product_seller_id'];
			$productPrice = $data['pricePurchase'];
			$shippingPrice = $data['shippingRate'];
			$totalPrice = $data['totalPrice'];
			$paymentData = json_encode($charge);
			$shippingAddressId = $data['shippig_address_id'];
			$billingAddressId = $data['billing_address_id'];
			$status = '1';
			$sellPrimaryID = $data['product_seller_id'];

			$commission_price = $request->session()->get('commissionPrice');
			$processing_fee = $request->session()->get('processingFee');

			$products_seller = ProductsSeller::find($sellPrimaryID);
			$seller_email = User::find($products_seller['user_id']);

			$savePayoutDetails = OrdersModel::create([
				'order_ref_number' => $orderRefNumber,
				'product_id' => $productID,
				'product_size_id' => $productSizeID,
				'user_id' => $userID,
				'bidder_id' => null,
				'seller_id' => $selllerID,
				'sold_by' => $products_seller['user_id'],
				'bought_by' => $userID,
				'price' => $productPrice,
				'total_price' => $totalPrice,
				'commission_price' => $commission_price,
				'shipping_price' => $shippingPrice,
				'processing_fee' => $processing_fee,
				'payment_data' => $paymentData,
				'shiping_address_id' => $shippingAddressId,
				'billing_address_id' => $billingAddressId,
				'payout_email' => $paypal_id,
				'currency_code' => $currencyCode,
				'status' => '1'
			]);

                
            ProductsSeller::where('id', $sellPrimaryID)->update(array('status' => 2));
			$orderID = $savePayoutDetails->id;
			// buyer mail 
            Mail::to($registredEmail)
			->send(new OrderGenerated($orderID));
			// sender mail
			Mail::to($seller_email['email'])
            ->send(new BuyNowforSeller($orderID));
        
        }
		$orderdetails = OrdersModel::with(['product'])->where(['id' => $orderID] )->first()->toArray();
		$data = array();
		$request->session()->flush();
		return view('website.orderbuy-detail', compact('data','orderdetails'));
		

	}


	// If user wants to BUY now[using promo code]
	public function promoChargePayment(Request $request)
	{
		//echo "<pre>"; print_r($request->session()->all()); echo "</pre>"; die('heerererer');
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		// find  admin id
		$admin_data = User::where(['is_admin' => 1])->first()->toArray();

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];
		$paypal_id = $tokenData['paypal_id'];
		
		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
            //   \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");



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

		$chargeAmount = $request->session()->get('totalPrice');
		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount

		$currencyCode = '';
		if ($request->session()->has('currencyCode')) {
			$currencyCode = $request->session()->get('currencyCode'); // get the dynamic currency from session
		} else {
			$currencyCode = 'CAD'; //if no session set then use default one
		}

		$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => $currencyCode,
			'customer' => $customerID,
		]);

		if ($charge->outcome->seller_message == 'Payment complete.') {

			$data = $request->session()->all();

			$orderRefNumber = time() . mt_rand() . $userID;
			$productID = $data['product_id'];
			$productSizeID = $data['product_size_id'];
			$userID = $userID;
			$productPrice = $data['pass_value'];
			$shippingPrice = $data['shippingRate'];
			$totalPrice = $data['totalPrice'];
			$paymentData = json_encode($charge);
			$shippingAddressId = $data['shippig_address_id'];
			$billingAddressId = $data['billing_address_id'];
			$status = '1';
			$sellPrimaryID = $admin_data['id'];

			$commission_price = $request->session()->get('commissionPrice');
			$processing_fee = $request->session()->get('processingFee');

			$savePayoutDetails = OrdersModel::create([
				'order_ref_number' => $orderRefNumber,
				'product_id' => $productID,
				'product_size_id' => $productSizeID,
				'user_id' => $userID,
				'bidder_id' => null,
				'seller_id' => null,
				'sold_by' => $sellPrimaryID,
				'bought_by' => $userID,
				'price' => $productPrice,
				'total_price' => $totalPrice,
				'commission_price' => $commission_price,
				'shipping_price' => $shippingPrice,
				'processing_fee' => $processing_fee,
				'payment_data' => $paymentData,
				'shiping_address_id' => $shippingAddressId,
				'billing_address_id' => $billingAddressId,
				'payout_email' => $paypal_id,
				'currency_code' => $currencyCode,
				'status' => '1'
			]);
			
			$orderID = $savePayoutDetails->id;
			// buyer mail 
            Mail::to($registredEmail)
			->send(new OrderGenerated($orderID));
			// sender mail
			Mail::to($admin_data['email'])
           	->send(new BuyNowforSeller($orderID));
        
        }
		$orderdetails = OrdersModel::with(['product'])->where(['id' => $orderID] )->first()->toArray();
		$data = array();
		$request->session()->flush();
		return view('website.orderbuy-detail', compact('data','orderdetails'));
		

	}


	// If user wants to BUY now[VIP User]
	public function vipChargePayment(Request $request)
	{
		//echo "<pre>"; print_r($request->session()->all()); echo "</pre>"; die('heerererer');
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		// find  admin id
		$admin_data = User::where(['is_admin' => 1])->first()->toArray();

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];
		$paypal_id = $tokenData['paypal_id'];
		
		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
           //    \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");



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

		$chargeAmount = $request->session()->get('totalPrice');
		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount

		$currencyCode = '';
		if ($request->session()->has('currencyCode')) {
			$currencyCode = $request->session()->get('currencyCode'); // get the dynamic currency from session
		} else {
			$currencyCode = 'CAD'; //if no session set then use default one
		}

		$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => $currencyCode,
			'customer' => $customerID,
		]);

		if ($charge->outcome->seller_message == 'Payment complete.') {

			$data = $request->session()->all();

			$orderRefNumber = time() . mt_rand() . $userID;
			$productID = $data['product_id'];
			$productSizeID = $data['product_size_id'];
			$userID = $userID;
			$productPrice = $data['pricePurchase'];
			$shippingPrice = $data['shippingRate'];
			$totalPrice = $data['totalPrice'];
			$paymentData = json_encode($charge);
			$shippingAddressId = $data['shippig_address_id'];
			$billingAddressId = $data['billing_address_id'];
			$status = '1';
			$sellPrimaryID = $admin_data['id'];

			$commission_price = $request->session()->get('commissionPrice');
			$processing_fee = $request->session()->get('processingFee');

			$savePayoutDetails = OrdersModel::create([
				'order_ref_number' => $orderRefNumber,
				'product_id' => $productID,
				'product_size_id' => $productSizeID,
				'user_id' => $userID,
				'bidder_id' => null,
				'seller_id' => null,
				'sold_by' => $sellPrimaryID,
				'bought_by' => $userID,
				'price' => $productPrice,
				'total_price' => $totalPrice,
				'commission_price' => $commission_price,
				'shipping_price' => $shippingPrice,
				'processing_fee' => $processing_fee,
				'payment_data' => $paymentData,
				'shiping_address_id' => $shippingAddressId,
				'billing_address_id' => $billingAddressId,
				'payout_email' => $paypal_id,
				'currency_code' => $currencyCode,
				'status' => '1',
				'vip_order' => '1'
			]);

			$orderID = $savePayoutDetails->id;
			//buyer mail 
            Mail::to($registredEmail)
			->send(new OrderGenerated($orderID));
			// sender mail
			Mail::to($admin_data['email'])
           	->send(new BuyNowforSeller($orderID));
        
        }
		$orderdetails = OrdersModel::with(['product'])->where(['id' => $orderID] )->first()->toArray();
		$data = array();
		$request->session()->flush();
		return view('website.orderbuy-detail', compact('data','orderdetails'));
		

	}


	// If user wants to SELL now [DIRECT SELL]
	public function deductPayment(Request $request)
	{

		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];
		$paypal_id = $tokenData['paypal_id'];
		
		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
          //    \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");
		
		
        $data = $request->session()->all();
        
        //echo"<pre>"; print_r($data); die;
        
        $stripeCustomerID = $data['stripe_customer_id'];



		$chargeAmount = $request->session()->get('totalPrice');

		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount


		$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => 'cad',
			'customer' => $stripeCustomerID,
		]);

		
        
        
        $orderRefNumber  = time().mt_rand().$userID;
            $productID = $data['product_id'];
            $productSizeID = $data['product_size_id'];
            $userID = $userID;
            $bidderID = $data['product_bidder_id'];
            $productPrice = $data['pricePurchase'];
            $shippingPrice = $data['shippingRate'];
            $totalPrice = $data['totalPrice'];
            $paymentData = json_encode($charge);
            $shippingAddressId = $data['shippig_address_id'];
            $billingAddressId = $data['billing_address_id'];
            $status = '1';
			$bidPrimaryId = $data['product_bidder_id'];
			
			$commission_price = $request->session()->get('commissionPrice');
			$processing_fee = $request->session()->get('processingFee');
			
			$products_buyer = ProductsBidder::find($bidPrimaryId);
			$buyer_email = User::find($products_buyer['user_id']);
            
            $savePayoutDetails = OrdersModel::create([
				'order_ref_number' => $orderRefNumber,
				'product_id' => $productID,
				'product_size_id' => $productSizeID,
				'user_id' => $userID,
				'seller_id' => NULL,
				'bidder_id' => $bidderID,
				'sold_by' => $userID,
				'bought_by' => $products_buyer['user_id'],
				'price' => $productPrice,
				'total_price' => $totalPrice,
				'commission_price'=> $commission_price,
				'shipping_price' => $shippingPrice,
				'processing_fee'=> $processing_fee,
				'payment_data' => $paymentData,
				'shiping_address_id' => $shippingAddressId,
				'billing_address_id' => $billingAddressId,
				'payout_email' => $paypal_id,
				'status' => '1'
			]);
            // Status is 2 for sell now
            ProductsBidder::where('id', $bidPrimaryId)->update(array('status' => 2));
            
			$orderID = $savePayoutDetails->id;
			// seller email
            Mail::to($registredEmail)
			->send(new OrderGenerated($orderID));
			// buyer email 
			Mail::to($buyer_email['email'])
			->send(new SellNowforBuyer($orderID));
			
			$orderdetails = OrdersModel::with(['product'])->where(['id' => $savePayoutDetails['id']] )->first()->toArray();
            $data = array();
			$request->session()->flush();
            return view('website.ordersell-detail',compact('data','orderdetails'));

	}


	public function saveCards(Request $request)
	{

		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		$stripeToken = $tokenData['stripeToken'];
		
		\Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
            //  \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");


		$customer = \Stripe\Customer::create([
			'source' => $stripeToken,
			'email' => $registredEmail,
		]);


		$customerID = $customer->id;
		$default_source = $customer->default_source;
		$paymentEmail = $customer->email;
		$invoicePrefix = $customer->invoice_prefix;
		$stripeDetails = json_encode($customer->sources->data);


		$saveDetails = CardsModel::create([
			'user_id' => $userID,
			'default_source' => $default_source,
			'stripe_customer_id' => $customerID,
			'stripe_payment_email' => $paymentEmail,
			'stripe_details' => $stripeDetails,
			'invoice_prefix' => $invoicePrefix,
			'default' => 0,
			'status' => 1
		]);

		if ($saveDetails->id) {

			return view('website.user-account');

		} else {

			return view('website.error');
		}
	}

	/*
	 * Charge customer for buying subscription
	 * @Params stripe_plan_id | customer data | amount details
	 * @Returns susbcription object
	 * */
	public function chargeSubcription(Request $request)
	{

		//STEP I :- Plan ID from stripe
		//$stripePlanId = 'hypex_monthly_1';
		$stripePlanId = env('STRIPE_TEST_PLAN_ID'); //PLAN ID FOR TESTING

		/*plan from database*/
		$plan_id = $request->session()->get('plan_id');
		$plan = SubscriptionPlan::where(['id' => $plan_id, 'status' => 1])->first();
		
		$user = Auth::user();
		$registredEmail = $user->email;
		$userID = $user->id;

		$tokenData = $request->all();

		//STEP II :- Create customer
		$stripeToken = $tokenData['stripeToken'];
		 \Stripe\Stripe::setApiKey("sk_test_h3t38JCIwFgq7KE5VL2Q5Vua");
        //  \Stripe\Stripe::setApiKey("sk_live_5cAUuzrUacn68l14vJ0FNV3d");
		$customer = \Stripe\Customer::create([
			'source' => $stripeToken,
			'email' => $registredEmail,
		]);

		$customerID = $customer->id;

		//STEP III :-
		\Stripe\Stripe::setApiKey('sk_test_h3t38JCIwFgq7KE5VL2Q5Vua');

		$subscription = \Stripe\Subscription::create([
			'customer' => $customerID,
			'items' => [['plan' => $stripePlanId]],
		]);

		$default_source = $customer->default_source;
		$paymentEmail = $customer->email;
		$invoicePrefix = $customer->invoice_prefix;
		$stripeDetails = json_encode($subscription); //assign subscribtion object from STRIPE

		//$card = current($customer->sources->data);

		$chargeAmount = '1';
		$stripeAmount = $chargeAmount * 100; // for stripe decimal amount

		$currencyCode = '';
		if ($request->session()->has('currencyCode')) {
			$currencyCode = $request->session()->get('currencyCode'); // get the dynamic currency from session
		} else {
			$currencyCode = 'CAD'; //if no session set then use default one
		}

		$charge = \Stripe\Charge::create([
			'amount' => $stripeAmount,
			'currency' => $currencyCode,
			'customer' => $customerID,
			'description' => 'Subsription, amount supplied by customer'
		]);
		//dd($charge);
		if ($charge->outcome->seller_message == 'Payment complete.') {

			$saveSubscriptionsDetails = SubscriptionsModel::create([
				'user_id' 			=> $userID,
				'plan_id' 			=> $plan_id,
				'price' 			=> $chargeAmount,
				'start_date'		=> \Carbon\Carbon::now()->toDateTimeString(),
				'end_date'  		=> \Carbon\Carbon::parse($user->start_date)->addMonths(1),
				'payment_gateway' 	=> 'Credit card - Stripe',
				'status' 			=> '1'
			]);

				if ($charge->paid == '1') {

					$saveSubscriptionPayoutDetails = SubscriptionPayoutModel::create([

						'subscription_id'		=> $saveSubscriptionsDetails->id,
						'stripe_plan_id'        => $stripePlanId,
						'default_source' 		=> $default_source,
						'stripe_customer_id' 	=> $customerID,
						'stripe_payment_email' 	=> $paymentEmail,
						'stripe_details' 		=> $stripeDetails,
						'invoice_prefix' 		=> $invoicePrefix
					]);

				}
			}

			$subscriptionsID = $saveSubscriptionsDetails->id;
			// user email
             Mail::to($registredEmail)
			 ->send(new BuySubscription($subscriptionsID));

			$subscriptiondetails = SubscriptionsModel::with(['plan'])->where(['id' => $saveSubscriptionsDetails->id] )->first()->toArray();
			$data = array();
			$request->session()->flush();
            return view('website.subscriptionbuy-detail',compact('data','subscriptiondetails'));
	}
}
