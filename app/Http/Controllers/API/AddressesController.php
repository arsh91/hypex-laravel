<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\API\SaveShippingAddressRequest;
use App\Http\Requests\API\SaveBillingAddressRequest;

use App\Http\Controllers\Controller;

use App\APIModels\CountriesModel;
use App\APIModels\ProvincesModel;
use App\APIModels\ProductSizeTypes;
use App\Models\UsersBillingAddressModel;
use App\Models\UsersShippingAddressModel;
use App\APIModels\CardsModel;
 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use Carbon\Carbon;

class AddressesController extends Controller
{
	public function addShippingReturnAddress(Request $request){
		$input = $request->all();
		$user_id = Auth::id();
		$shipping = $input['shipping_address'];
		if(isset($input['return_address']) && !empty($input['return_address'])){
			$returnAddress = $input['return_address'];
		}
		$return = $input['is_return'];	
		if(!empty($shipping)){ 
		if($shipping['default'] == 1){ UsersShippingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }
			$saveUsersShippingAddress = UsersShippingAddressModel::create([
				'user_id' => $user_id,
				'first_name' => $shipping['first_name'],
				'last_name' => $shipping['last_name'],
				'phone_number' => $shipping['phone_number'],
				'province' => $shipping['province'],
				'full_address' => $shipping['full_address'],
				'street_city' => $shipping['street_city'],
				'province' => $shipping['province'],
				'country' => $shipping['country'],
				'zip_code' => $shipping['zip_code'],
				'default' => $shipping['default'],
				'status' => 1
			]);
			$data['shipping_address_id'] = $saveUsersShippingAddress->id;
	    }
		if($saveUsersShippingAddress->id){
			if($return == 1){
				if($shipping['default'] == 1){ UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }
				$saveUsersBillingAddress = UsersBillingAddressModel::create([
					'user_id' => $user_id,
					'first_name' => $shipping['first_name'],
					'last_name' => $shipping['last_name'],
					'phone_number' => $shipping['phone_number'],
					'full_address' => $shipping['full_address'],
					'street_city' => $shipping['street_city'],
					'province' => $shipping['province'],
					'country' => $shipping['country'],
					'zip_code' => $shipping['zip_code'],
					'default' => $shipping['default'],
					'status' => 1
				]);
			$data['return_address_id'] = $saveUsersBillingAddress->id;
			}else{
				if(isset($input['return_address']) && !empty($input['return_address'])){
				if($returnAddress['default'] == 1){ UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }
					$saveUsersBillingAddress = UsersBillingAddressModel::create([
						'user_id' => $user_id,
						'first_name' => $returnAddress['first_name'],
						'last_name' => $returnAddress['last_name'],
						'phone_number' => $returnAddress['phone_number'],
						'full_address' => $returnAddress['full_address'],
						'street_city' => $returnAddress['street_city'],
						'province' => $returnAddress['province'],
						'country' => $returnAddress['country'],
						'zip_code' => $returnAddress['zip_code'],
						'default' => $returnAddress['default'],
						'status' => 1
				]);
				$data['return_address_id'] = $saveUsersBillingAddress->id;
				}
			}
		}
		if(!empty($data)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address added successfully.','data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
		}
	}
	public function addShippingAddress(Request $request){
		$input = $request->all();
		$user_id = Auth::id();
		if($input['default'] == 1){ UsersShippingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }
			$saveUsersShippingAddress = UsersShippingAddressModel::create([
				'user_id' => $user_id,
				'first_name' => $input['first_name'],
				'last_name' => $input['last_name'],
				'phone_number' => $input['phone_number'],
				'province' => $input['province'],
				'full_address' => $input['full_address'],
				'street_city' => $input['street_city'],
				'province' => $input['province'],
				'country' => $input['country'],
				'zip_code' => $input['zip_code'],
				'default' => $input['default'],
				'status' => 1
			]);
		$data['shipping_address_id'] =  $saveUsersShippingAddress->id;
		if($saveUsersShippingAddress->id){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address added successfully.','data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
		}
	}
	
	public function addReturnAddress(Request $request){
		$input = $request->all();
		$user_id = Auth::id();
		if($input['default'] == 1){ UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); }		
		$saveUsersBillingAddress = UsersBillingAddressModel::create([
				'user_id' => $user_id,
				'first_name' => $input['first_name'],
				'last_name' => $input['last_name'],
				'phone_number' => $input['phone_number'],
				'full_address' => $input['full_address'],
				'street_city' => $input['street_city'],
				'province' => $input['province'],
				'country' => $input['country'],
				'zip_code' => $input['zip_code'],
				'default' => $input['default'],
				'status' => 1
			]);
		$data['return_address_id'] =  $saveUsersBillingAddress->id;	
		if($saveUsersBillingAddress->id){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address added successfully.', 'data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
		}
	}
	
	public function getShippingAddress(){
		$user_id = Auth::id();
		$shippingAddress = array();
		if(!empty($user_id)){		
		    $getShippingAddresses = UsersShippingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->orderBy('created_at', 'desc')->get()->toArray();
			foreach($getShippingAddresses as $getShippingAddress){
				
				$getShippingAddress['country_abbreviation'] = $getShippingAddress['country'];
				$getShippingAddress['country_id'] = $this->getCountryId($getShippingAddress['country']);
				$getShippingAddress['country'] = $this->getCountry($getShippingAddress['country']);
				$getShippingAddress['province_abbreviation'] = $getShippingAddress['province'];
				$getShippingAddress['province_id'] = $this->getProvinceId($getShippingAddress['province']);
			    $getShippingAddress['province'] = $this->getProvince($getShippingAddress['province']);
			    
				$shippingAddress[] = $getShippingAddress;
			}
		}
		if(!empty($shippingAddress)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Shipping Address retrieved successfully.','data'=>$shippingAddress]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No shipping addresses availabl.','data'=>$shippingAddress]);
		}
	}
	
	
	/*** return address ***/
	public function getReturnAddress(){
		$user_id = Auth::id();
		$billingAddress = array();
		if(!empty($user_id)){		
			$getBillingAddresses = UsersBillingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->orderBy('created_at', 'desc')->get()->toArray();
			foreach($getBillingAddresses as $getBillingAddress){
				$getBillingAddress['country_abbreviation'] = $getBillingAddress['country'];
				$getBillingAddress['country_id'] = $this->getCountryId($getBillingAddress['country']);
				$getBillingAddress['country'] = $this->getCountry($getBillingAddress['country']);
				$getBillingAddress['province_abbreviation'] = $getBillingAddress['province'];
				$getBillingAddress['province_id'] = $this->getProvinceId($getBillingAddress['province']);
			    $getBillingAddress['province'] = $this->getProvince($getBillingAddress['province']);
			    
				$billingAddress[] = $getBillingAddress;
			}
		}
		if(count($billingAddress)>0){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Return Addresses retrieved successfully.','data'=>$billingAddress]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No return addresses available.','data'=>$billingAddress]);
		}
	}
	
	/**** get Country name ***/
	function getCountry($abb){
		$country = CountriesModel::select('name')->where('abbreviation',$abb)->get()->first();
		return $country['name']; 
	}
	function getCountryId($abb){
		$country = CountriesModel::select('id')->where('abbreviation',$abb)->get()->first();
		return $country['id']; 
	}
	
	/**** get Province name ***/
	function getProvince($abb){
		$province = ProvincesModel::select('name')->where('abbreviation',$abb)->get()->first();
		return $province['name']; 
	}
	function getProvinceId($abb){
		$province = ProvincesModel::select('id')->where('abbreviation',$abb)->get()->first();
		return $province['id']; 
	}
	
	public function setDefaultShippingAddress(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
			if($input['default'] == 1){ 
				UsersShippingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); 
				if(UsersShippingAddressModel::where([['user_id','=', $user_id],['id','=',$input['id']]])->update(array('default' => 1))){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to update the address.','data'=>(object)[]]);
				} 
			}else{
				$getShippingAddresses = UsersShippingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
				if(count($getShippingAddresses)==1){ 
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'At least one address must be set as default.','data'=>(object)[]]); 
				}else{
					if(UsersShippingAddressModel::where([['user_id', '=', $user_id],['id','=', $input['id']]])->update(array('default' => 0))){
						$defaultShippingAddresses = UsersShippingAddressModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $input['id']]])->orderBy('created_at', 'desc')->get()->first();
						$defaultShippingAddresses->default = 1;
						if($defaultShippingAddresses->save())
						 { return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]); }
					}else{
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to update the address.','data'=>(object)[]]);
					} 
				}
			}		
	}
	
	public function setDefaultReturnAddress(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
			if($input['default'] == 1){ 
				UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); 
				if(UsersBillingAddressModel::where([['user_id','=', $user_id],['id','=',$input['id']]])->update(array('default' => 1))){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to update the address.','data'=>(object)[]]);
				} 
			}else{
				$getBillingAddresses = UsersBillingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
				if(count($getBillingAddresses)==1){ 
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'At least one address must be set as default.','data'=>(object)[]]); 
				}else{
					if(UsersBillingAddressModel::where([['user_id', '=', $user_id],['id','=', $input['id']]])->update(array('default' => 0))){
						$defaultBillingAddresses = UsersBillingAddressModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $input['id']]])->orderBy('created_at', 'desc')->get()->first();
						$defaultBillingAddresses->default = 1;
						if($defaultBillingAddresses->save()){
							return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]);
						}
					}else{
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to update the address.','data'=>(object)[]]);
					} 
				}
			}
	}
	
	function getCountries(){
		$countries = CountriesModel::get()->toArray();
		
		if(count($countries)>0){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Countries retrieved successfully.', 'data'=>$countries]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No Data.','data'=>$countries]);
		}
		
	}
	
	function getProvinces(Request $request){
		$input = $request->all();
		$country_id = $input['country_id'];	
		if(!empty($country_id)){
			$provinces = ProvincesModel::where('country_id',$country_id)->get()->toArray();
			if(count($provinces)>0){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Provinces retrieved successfully.', 'data'=>$provinces]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No Data.','data'=>$provinces]);
			}
		}
	}
	
	public function editShippingAddress(Request $request){
		$user_id = Auth::id();
		if($user_id != NULL || $user_id != ''){
		$input = $request->all();
			if($input['default'] == 1){ 
				UsersShippingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); 	
			}
			$get_shipping_detail = UsersShippingAddressModel::findorFail($input['id']);
			$get_shipping_detail->first_name = $input['first_name'];
			$get_shipping_detail->last_name = $input['last_name'];
			$get_shipping_detail->phone_number = $input['phone_number'];
			$get_shipping_detail->full_address = $input['full_address'];
			$get_shipping_detail->street_city = $input['street_city'];
			$get_shipping_detail->province = $input['province'];
			$get_shipping_detail->country = $input['country'];
			$get_shipping_detail->zip_code = $input['zip_code'];
			$get_shipping_detail->default = $input['default'];
			$get_shipping_detail->created_at = Date('Y-m-d H:i:s');
            $get_shipping_detail->updated_at = Date('Y-m-d H:i:s');
			$get_shipping_detail->status = 1;
			if($get_shipping_detail->save()){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address updated successfully.', 'data'=>(object)[]]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
			
		}
	}
	
	public function editReturnAddress(Request $request){
		$user_id = Auth::id();
		if($user_id != NULL || $user_id != ''){
		$input = $request->all();
			if($input['default'] == 1){ 
				UsersBillingAddressModel::where('user_id', $user_id)->update(array('default' => 0)); 	
			}
			$get_shipping_detail = UsersBillingAddressModel::findorFail($input['id']);
			$get_shipping_detail->first_name = $input['first_name'];
			$get_shipping_detail->last_name = $input['last_name'];
			$get_shipping_detail->phone_number = $input['phone_number'];
			$get_shipping_detail->full_address = $input['full_address'];
			$get_shipping_detail->street_city = $input['street_city'];
			$get_shipping_detail->province = $input['province'];
			$get_shipping_detail->country = $input['country'];
			$get_shipping_detail->zip_code = $input['zip_code'];
			$get_shipping_detail->default = $input['default'];
			$get_shipping_detail->created_at = Date('Y-m-d H:i:s');
            $get_shipping_detail->updated_at = Date('Y-m-d H:i:s');
			$get_shipping_detail->status = 1;
			if($get_shipping_detail->save()){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address updated successfully.', 'data'=>(object)[]]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
			}
			
		}
	}
	
	public function deleteShippingAddress(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
		$id = $input['id'];
		if($id != NULL || $id != ''){
				$getShippingAddresses = UsersShippingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
				if(count($getShippingAddresses)==1){
					
					if(UsersShippingAddressModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
						return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address deleted successfully.', 'data'=>(object)[]]);
					}else{
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
					}
				}else{
					$defaultShippingAddresses = UsersShippingAddressModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $id]])->orderBy('created_at', 'desc')->get()->first();
					$defaultShippingAddresses->default = 1;
					if($defaultShippingAddresses->save()){
						if(UsersShippingAddressModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
							return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address deleted successfully.', 'data'=>(object)[]]);
						}
					}else{
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
					}
					
				}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Address/card does not exists.','data'=>(object)[]]);
		}
	}
	
	public function deleteReturnAddress(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
		$id = $input['id'];
		if($id != NULL || $id != ''){
			$getShippingAddresses = UsersBillingAddressModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
			if(count($getShippingAddresses)==1){
				if(UsersBillingAddressModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address deleted successfully.', 'data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
			}else{
				$defaultBillingAddresses = UsersBillingAddressModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $input['id']]])->orderBy('created_at', 'desc')->get()->first();
				$defaultBillingAddresses->default = 1;
				if($defaultBillingAddresses->save()){
					if(UsersBillingAddressModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
						return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Address deleted successfully.', 'data'=>(object)[]]);
					}
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
			}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Address/card does not exists.','data'=>(object)[]]);
		}
	}
	
	/****** cards Functions ***/
	public function getCards(){
		$userID = Auth::id();
		if($userID != NULL || $userID != ''){
			 $getCards = CardsModel::select('id','stripe_details','default')->where([['user_id','=',$userID],['status', '=', '1']])->orderBy('created_at', 'desc')->get()->toArray();
			 $cards = array();
			 if(count($getCards)>0){
				 $cards = array();
				 foreach($getCards as $card){
					 $cardDetail['id'] = $card['id'];
					 $customer = json_decode($card['stripe_details']); 					 
					 $cardDetail['customer_id'] = $customer[0]->customer;
					 $cardDetail['name'] = $customer[0]->name; 
					 $cardDetail['last4'] = $customer[0]->last4; 
					 $cardDetail['exp_month'] = $customer[0]->exp_month; 
					 $cardDetail['exp_year'] = $customer[0]->exp_year; 
					 $cardDetail['default'] = $card['default'];
					 $cards[] = $cardDetail;
				 }
			 }
		}
		if(count($cards)>0){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Cards retrieved successfully.','data'=>$cards]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No saved cards.','data'=>$cards]);
		}
	}
	
	public function setDefaultCard(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
			if($input['default'] == 1){ 
				CardsModel::where('user_id', $user_id)->update(array('default' => 0)); 
				if(CardsModel::where([['user_id','=', $user_id],['id','=',$input['id']]])->update(array('default' => $input['default']))){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to set as default.','data'=>(object)[]]);
				}
			}
			else{
				$getCards = CardsModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
				if(count($getCards)==1){ 
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'At least one card must be set as default.','data'=>(object)[]]); 
				}else{
					if(CardsModel::where([['user_id', '=', $user_id],['id','=', $input['id']]])->update(array('default' => 0))){
						$defaultCard = CardsModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $input['id']]])->orderBy('created_at', 'desc')->get()->first();
						$defaultCard->default = 1;
						if($defaultCard->save()){
							return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Updated successfully.','data'=>(object)[]]);
						}
					}else{
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Not able to set as default.','data'=>(object)[]]);
					} 
				}
			}			
					
	}
	
	public function deleteCard(Request $request){
		$user_id = Auth::id();
		$input = $request->all();
		$id = $input['id'];
		
		if($id != NULL || $id != ''){
			$getCards = CardsModel::where([['user_id','=',$user_id],['status', '=', '1']])->get()->toArray();
			if(count($getCards)==1){
				if(CardsModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Card deleted successfully.', 'data'=>(object)[]]);
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
			}else{
				$defaultCard = CardsModel::where([['user_id','=',$user_id],['status', '=', '1'],['id', '!=', $input['id']]])->orderBy('created_at', 'desc')->get()->first();
				$defaultCard->default = 1;
				if($defaultCard->save()){
					if(CardsModel::where([['user_id', $user_id],['id',$id]])->update(array('status' => 0))){
						return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Card deleted successfully.', 'data'=>(object)[]]);
					}
				}else{
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
				}
			}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Address/card does not exists.','data'=>(object)[]]);
		}
		
		
	}
	
	
	
	
	
}
