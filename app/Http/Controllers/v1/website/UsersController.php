<?php

namespace App\Http\Controllers\v1\website;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddReturnAddressRequest;
use App\Http\Requests\UpdateReturnAddressRequest;
use Illuminate\Mail\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\CardsModel;
use App\Models\ProductsBidder;
use App\Models\ProductSellerModel;
use App\APIModels\PayoutInfoModel; 
use App\Models\OrdersModel;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersShippingAddressModel;
use App\Models\UsersBillingAddressModel;
use DB;
//use Akaunting\Money\Currency;
//use Akaunting\Money\Money;

use Currency;
class UsersController extends Controller
{
    
	/**
     * Show the application myProfile.
     *
     * @return \Illuminate\Http\Response
     */
    
	public function myProfile(){
		
		$id = Auth::id();
		if($id != NULL || $id != ''){
			
			$currentuser = User::find($id);
			$data['first_name'] = $currentuser->first_name;
			$data['last_name'] = $currentuser->last_name;
			$data['email'] = $currentuser->email;
			$data['user_name'] = $currentuser->user_name;
			$data['phone'] = $currentuser->phone;
			$data['city'] = $currentuser->city;
			$data['state'] = $currentuser->state;
			$data['country'] = $currentuser->country;
			$data['postal_code'] = $currentuser->postal_code;
			
			return view('website.my-profile', $data);
			
		}else{
			
			return back()->withErrors(['Please login !!']);
		}
	}
	
	
	/**
     * Show the application updateProfile.
     *
     * @return \Illuminate\Http\Response
     */
    
	public function updateProfile(UpdateProfileRequest $request){
		

		$id = Auth::id();
		if($id != NULL || $id != ''){
			
			$validated = $request->validated();
			$user = Auth::user();
			
			$user->first_name = $validated['first_name'];
			$user->last_name = $validated['last_name'];
			$user->user_name = $validated['user_name'];
			$user->email = $validated['email'];
			$user->phone = $validated['phone'];
			$user->city = $validated['city'];
			$user->state = $validated['state'];
			$user->country = $validated['country'];
			$user->postal_code = $validated['postal_code'];
			
			if($user->save()){
				return redirect('/my-profile')->with('success','Profile updated successfully !!');
			}else{
				return back()->withErrors(['Some Technical Error Occurred !!']);
			}
			
			
		}else{
			
			return back()->withErrors(['Please login !!']);
		}
	}

	public function single(){
		$response = Currency::convert('USD','BDT',10);
		print_r($response); die;
		return response()->json($response);
	}


	/**
	 * Account section.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function userAcount(){
		/*$response = Currency::convert('USD','BDT',10);
		echo $curRate =  response()->json($response);*/
		//$response = Currency::convert('USD','BDT',10);
		//print_r($response['convertedAmount']);

		$id = Auth::id();
		if($id != NULL || $id != ''){
			$currentuser = User::find($id);
			$userData['email'] = $currentuser->email;
			$userData['password'] = $currentuser->password;

			// get open bids informatin
			$openbidds = ProductsBidder::with(['product','size'])->where(['status' => 1, 'user_id' => $id])->groupBy('product_id')->get(['product_id',DB::raw('MAX(bid_price) as max_bid')])->toArray();

			$openbidds = ProductsBidder::with(['product','size'])->where(['status' => 1, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray();
			
			// get In-progress bids informatin
			$progressbidds = ProductsBidder::with(['product','size'])->where(['status' =>5, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray();

			$progressbidds = OrdersModel::with(['product','procategory','brand'])->where(['bought_by' => $id,'status' =>1])->orderby('id', 'desc')->get()->toArray();

			$progBidCount = count($progressbidds);

			// get buying informatin

			$buyinghistory = OrdersModel::with(['product','procategory','brand'])->where(['bought_by' => $id,'status' =>2])->orderby('id', 'desc')->get()->toArray();


			// get open selling offer informatin
			$opensells = ProductSellerModel::with(['product','size'])->where(['status' => 1, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray(); 

			// get In-progress selling offer informatin
			$progresssells = OrdersModel::with(['ordershipped','product','procategory','brand'])->where(['sold_by' => $id,'status' =>1])->orderby('id', 'desc')->get()->toArray();

			// get buying informatin
			
			$sellinghistory = OrdersModel::with(['ordershipped','product','procategory','brand'])->where(['sold_by' => $id,'status' =>2])->orderby('id', 'desc')->get()->toArray(); 

			//echo "<pre>"; print_r($progressbidds); echo "</pre>"; exit;

			//return view('website.user-account',compact('openbidds','progressbidds','buyinghistory','opensells','progresssells','sellinghistory'));

			//get bids information
			$productBidorders = ProductsBidder::with(['product', 'size'])
				->where(['user_id' => $id])
				->orderby('id', 'desc')->get()->toArray();

			//GET SHIPPING ADDRESS
			$userShippingAddr = UsersShippingAddressModel::where('user_id', $id)->get()->toArray();
			$shipAdd = count($userShippingAddr);

			//GET BILLING ADDRESS
			$userBillingAddr = UsersBillingAddressModel::where('user_id', $id)->get()->toArray();
			$billAdd = count($userBillingAddr);

			//echo "<pre>"; print_r($sellinghistory); echo "</pre>";
			//die();


			//return view('website.user-account', ['shipAdd'=>$shipAdd, 'billAdd'=>$billAdd]);
			return view('website.user-account',compact('openbidds','shipAdd','billAdd','progressbidds', 'progBidCount', 'buyinghistory','opensells','progresssells','sellinghistory','currentuser'));

		}else{
			return back()->withErrors(['Please login !!']);
		}
	}

	/**
	 * Update user's shipping address section.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updateShippingAddress(Request $request, $shipAddr_id=null){
		//echo $shipAddr_id; exit;
		$user_id = Auth::id();
		if($request->isMethod('POST')){
			if($user_id != NULL || $user_id != ''){
				$data = $request->all();

				if(isset($data['default'])){				
					if($data['default'] == 1){
						UsersShippingAddressModel::where('user_id', $user_id)->update(array('default' => 0));
					}
				}else{
					$data['default'] = '0';
				}

				$get_shipping_detail = UsersShippingAddressModel::findorFail($shipAddr_id);
				$get_shipping_detail->first_name = $data['first_name'];
				$get_shipping_detail->last_name = $data['last_name'];
				$get_shipping_detail->phone_number = $data['phone_number'];
				$get_shipping_detail->full_address = $data['full_address'];
				$get_shipping_detail->street_city = $data['street_city'];
				$get_shipping_detail->province = $data['province'];
				$get_shipping_detail->country = $data['country'];
				$get_shipping_detail->zip_code = $data['zip_code'];
				$get_shipping_detail->default = $data['default'];
				$get_shipping_detail->created_at = Date('Y-m-d H:i:s');
				$get_shipping_detail->updated_at = Date('Y-m-d H:i:s');
				$get_shipping_detail->status = 1;
				if($get_shipping_detail->save()){
					return redirect('/view-shipping-address')->with('success','Shipping Address Updated Successfully !!');
				}else{
					return back()->withErrors(['Some Error Occurred !!']);
				}

			}
		}else{
			if($user_id != NULL || $user_id != '') {
				$userShippingAddr = UsersShippingAddressModel::where('user_id', $user_id)
					->where('id', $shipAddr_id)->get()->first()->toArray();
				//echo "<pre>"; print_r($userShippingAddr); echo "</pre>"; exit;
				return view('website.update-shipping-address', ['userShipAddr'=>$userShippingAddr, 'shipAddr_id'=>$shipAddr_id]);
			}else{
				return back()->withErrors(['Please login !!']);
			}
		}

	}

	/**
	 * Add user's shipping address section.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function addShippingAddress(Request $request){
		if($request->isMethod('POST')){
			$data = $request->all();
			//echo "<pre>"; print_r($data); echo "</pre>"; exit;
			$user_id = Auth::id();
			$default = '';
			if(in_array('default', $data)){
				$default = $data['default'];
			}else{
				$default = 0;
			}

			$saveUsersShippingAddress = UsersShippingAddressModel::create([
				'user_id' => $user_id,
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'phone_number' => $data['phone_number'],
				'province' => $data['province'],
				'full_address' => $data['full_address'],
				'street_city' => $data['street_city'],
				'province' => $data['province'],
				'country' => $data['country'],
				'zip_code' => $data['zip_code'],
				'default' => $default,
				'status' => 1
			]);
			$data['shipping_id'] =  $saveUsersShippingAddress->id;
			if($saveUsersShippingAddress->id){
				//echo "Address added successfully !!";
				return redirect('/user-account')->with('success','Shipping Address Added Successfully !!');
			}else{
				return back()->withErrors(['Some Error Occurred !!']);
			}

		}else{
			//print_r('normal view');
			return view('website.add-shipping-address');
		}
		
	}

    
    // Function to Save Payment Cards
    // of Users
    public function savePayment(){
        
        ini_set('memory_limit','-1');
        
        $loggedInUser = Auth::id();
        $savedCards = CardsModel::where('user_id', $loggedInUser)->select('stripe_details','invoice_prefix')->get()->toArray();
        
        if(!empty($savedCards)){
            foreach($savedCards as $key=>$value){
                
                $stripeData = $value['stripe_details'];
                $data[$value['invoice_prefix']] = json_decode($stripeData);
                
            }
            
            $finalData['stripeData'] = $data;
            return view('website.users-payments',$finalData);
        }else{
            $finalData['stripeData'] = array();
            return view('website.users-payments',$finalData);
        }
        
	}
	

	// suscription check
	public function subscriptioncheck(){
		$userId = Auth::id();
		if($userId){
			$userSubcribed = User::with(['subcription'])->where('id', $userId)->get()->first()	->toArray();
			if(is_null($userSubcribed['subcription'])){
				//echo "please pay";
				$data = 0;
				return $data;
			}else{
				//echo "you alreday have";
				$data = 1;
				return $data;
			}
		}
	}


	/// subscription payment

	public function subscriptionPayment(Request $request,$id){
        
		ini_set('memory_limit','-1');
		$id = base64_decode($id);
		$request->session()->put('plan_id', $id);
        return view('website.subscription-payments');
    
    }

	
	/**
	 * View user's shipping address section.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function viewShippingAddress(Request $request){
		$user_id = Auth::id();
		$userShippingAddr = UsersShippingAddressModel::with(['users'])
			->where('user_id', $user_id)
			->where('status', '1')->get()->toArray();
		//echo "<pre>"; print_r($userShippingAddr); echo "</pre>";
		return view('website.view-shipping-address', ['userShippingAddr'=> $userShippingAddr]);
	}

	public function deleteShippingAddress(Request $request, $shipAddID=null){
		$user_id = Auth::id();
		$data = $request->all();

		if($shipAddID != NULL || $shipAddID != ''){

			if(UsersShippingAddressModel::where([['user_id', $user_id],['id',$shipAddID]])->update(array('status' => 0))){
				return redirect('/view-shipping-address')->with('success','Address deleted successfully !!');
			}else{
				return back()->withErrors(['Some Error Occurred !!']);
			}
		}else{
			return redirect('/view-shipping-address')->with('success','Soem issue with the record !!');
		}
	}

	
    public function changeoldPassword(Request $request){
		$user = Auth::user();
        if ($request->isMethod('post')) {
            
            if(!Hash::check($request->opassword, $user->password)){ // Matching old password
                return back()
                            ->with('error','The specified password does not match the database password');
            }else{
                $password = Hash::make($request->cpassword); // Encrypting new password
				User::where('id', $user->id)->update(['password' => $password]);
				return redirect('/user-account')->with('success','Password changed successfully');
            }
		}
		return view('website.change-password');
	}
	


	/**
	 * View page for user's retrun address form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function addReturnAddress(){
		$user_id = Auth::id();
		$userReturnAddr = UsersBillingAddressModel::with(['users'])->where('user_id', $user_id)->get()->toArray();
		$returnAddCount = count($userReturnAddr);
		return view('website.add-return-address',['userReturnAddr'=>$returnAddCount]);
	}

	/**
	 * Add user's shipping address section.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function saveReturnAddress(AddReturnAddressRequest $request){
		$user_id = Auth::id();
		$default = '';
		if($user_id != NULL || $user_id != ''){
			$validated = $request->validated();
			//echo "<pre>"; print_r($validated); echo "</pre>"; exit;
			if(in_array('default', $validated)){
				$default = $validated['default'];
			}else{
				$default = 0;
			}
			if($validated['default'] == 1){ UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }
			$saveUsersBillingAddress = UsersBillingAddressModel::create([
				'user_id' => $user_id,
				'first_name' => $validated['first_name'],
				'last_name' => $validated['last_name'],
				'phone_number' => $validated['phone_number'],
				'full_address' => $validated['full_address'],
				'street_city' => $validated['street_city'],
				'province' => $validated['province'],
				'country' => $validated['country'],
				'zip_code' => $validated['zip_code'],
				'default' => $validated['default'],
				'status' => 1
			]);
			$data['return_id'] =  $saveUsersBillingAddress->id;
			if($saveUsersBillingAddress->id){
				return redirect('/user-account')->with('success','Return Address Added Successfully !!');
			}else{
				return back()->withErrors(['Some Error Occurred !!']);
			}
		}else{

			return back()->withErrors(['Please login !!']);
		}
	}

	public function viewReturnAddress(){
		$user_id = Auth::id();
		$userReturnAddr = UsersBillingAddressModel::with(['users'])
			->where('user_id', $user_id)
			->where('status', '1')
			->get()->toArray();
		$count = count($userReturnAddr);
		//echo "<pre>"; print_r($userReturnAddr); echo "</pre>";
		return view('website.view-return-address', ['userShippingAddr'=> $userReturnAddr, 'count' => $count]);
	}

	public function updateReturnAddress(Request $request, $returnAddr_id=null){
		$user_id = Auth::id();
		if($user_id != NULL || $user_id != '') {
			$userReturnAddr = UsersBillingAddressModel::where('user_id', $user_id)
				->where('id', $returnAddr_id)->get()->first()->toArray();
			//echo "<pre>"; print_r($userReturnAddr); echo "</pre>"; exit;
			return view('website.update-return-address', ['userReturnAddr'=>$userReturnAddr, 'returnAddr_id'=>$returnAddr_id]);
		}else{
			return back()->withErrors(['Please login !!']);
		}
		//return view('website.update-return-address');
	}

	public function editReturnAddress(UpdateReturnAddressRequest $request, $returnAddr_id=null){
		$user_id = Auth::id();
		$validated = $request->validated();
		//echo "<pre>"; print_r($validated); echo "</pre>"; exit;
		if($user_id != NULL || $user_id != ''){
			$validated = $request->all();

			if(isset($validated['default'])){				
				if($validated['default'] == 1){
					UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0));
				}
			}else{
				$validated['default'] = '0';
			}

			$get_shipping_detail = UsersBillingAddressModel::findorFail($returnAddr_id);
			$get_shipping_detail->first_name = $validated['first_name'];
			$get_shipping_detail->last_name = $validated['last_name'];
			$get_shipping_detail->phone_number = $validated['phone_number'];
			$get_shipping_detail->full_address = $validated['full_address'];
			$get_shipping_detail->street_city = $validated['street_city'];
			$get_shipping_detail->province = $validated['province'];
			$get_shipping_detail->country = $validated['country'];
			$get_shipping_detail->zip_code = $validated['zip_code'];
			$get_shipping_detail->default = $validated['default'];
			$get_shipping_detail->created_at = Date('Y-m-d H:i:s');
			$get_shipping_detail->updated_at = Date('Y-m-d H:i:s');
			$get_shipping_detail->status = 1;
			if($get_shipping_detail->save()){
				return redirect('/view-return-address')->with('success','Return Address Updated Successfully !!');
			}
			else{
				return back()->withErrors(['Some Error Occurred !!']);
			}

		}else{
			return back()->withErrors(['Please login !!']);
		}

	}


	public function deleteReturnAddress(Request $request, $returnAddID=null){
		$user_id = Auth::id();
		if($returnAddID != NULL || $returnAddID != ''){

			if(UsersBillingAddressModel::where([['user_id', $user_id],['id',$returnAddID]])->update(array('status' => 0))){
				return redirect('/view-return-address')->with('success','Address deleted successfully !!');
			}else{
				return back()->withErrors(['Some Error Occurred !!']);
			}
		}else{
			return redirect('/view-return-address')->with('success','Soem issue with the record !!');
		}
	}
    
    
    
    // Function to Remove Payment Cards
    // of Users
    public function removeCard($prefix=null){
        
        if($prefix != null){
            
            $check = CardsModel::where('invoice_prefix', $prefix)->delete();
            return redirect('/user-account')->with('success','Card Removed Successfully !!');
        }
        
    }
    
    
    public function getPayoutInfo(){
        
		$userID = Auth::id();
		$getEmails = PayoutInfoModel::where([['user_id','=',$userID],['status', '=', '1']])->first();
        
        if($getEmails){
            $payoutInfo['paydetails'] = $getEmails->toArray();
            return view('website.users-payout',$payoutInfo);
        }else{
            $payoutInfo['paydetails'] = array();
            return view('website.users-payout',$payoutInfo);
        }        

	}
    
    
    public function savePayoutInfo(Request $request){
        
        $userID = Auth::id();
        $email = $request['email'];
        
		$getEmails = PayoutInfoModel::where('user_id',$userID)->get()->first();
		if(!empty($getEmails)){
			PayoutInfoModel::where('user_id','=', $userID)->update(array('payout_email' => $email));
			$payoutId= $getEmails->id;
		}else{
			$saveDetails = PayoutInfoModel::create([
					'user_id' => $userID,
					'payout_email' => $email,
					'status' => 1
				]);
			$payoutId= $saveDetails->id;
		}
        
        if($payoutId){
             return redirect('/user-account')->with('success','Payout Info Saved Successfully !!');
        }
    }

    /*Change price according to the new currency set in session
    * @Params newCurrency, $oldCurrency, $currencyAmount
     * @Return newPrice | numeric with currency code
    */
	public static function changePrice($newCurrency=null, $oldCurrency=null, $currencyAmount=null){
		$currencyAmount;
		$newPrice = '';
		if($newCurrency != $oldCurrency){ //USD != CAD change the price
			$convertResponse = Currency::convert($oldCurrency, $newCurrency, $currencyAmount);
			$newPrice = $newCurrency.' '.$convertResponse['convertedAmount'];

		}else{ //eg USD == USD then display the old price
			$newPrice = $oldCurrency.' '.$currencyAmount;
		}
		return $newPrice;
	}

	/*
	 * Ajax function to save currency code into session
	 * @Params Currency Code
	 * @Returns Session Updated
	 * */
    public function saveCurrencyToSession(Request $request){
	    $code = $request->currencyCode;
	    $request->session()->put('currencyCode', $code);
	    $ss = $request->session()->get('currencyCode');
	    return response()->json($ss);
	    //exit;
    }
}
