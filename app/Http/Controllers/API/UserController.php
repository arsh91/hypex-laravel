<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Http\Requests\API\UpdateProfileRequest;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\APIModels\UserSocialProfile;  
use App\APIModels\UserProfile; 
use App\APIModels\PayoutInfoModel; 
use App\APIModels\CardsModel; 
use App\Models\UsersShippingAddressModel; 
use App\Models\UsersBillingAddressModel; 
use App\APIModels\PasswordReset;
use App\Models\ProductsBidder;
use App\Models\ProductSellerModel;
use App\Models\OrdersModel;
use App\APIModels\CountriesModel;
use App\APIModels\UserNotification;
use App\APIModels\ProvincesModel;
use App\Helpers\WebHelper;

use Illuminate\Support\Facades\DB;
use Validator;
class UserController extends Controller 
{
	//public $successStatus = 200;
	
	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request){  
		if(!empty($request->input('user_name'))&&(!empty($request->input('password')))){ 
			if(filter_var($request->input('user_name'), FILTER_VALIDATE_EMAIL)) {
				if(Auth::attempt(['email' => $request->input('user_name'), 'password' => $request->input('password'),'status' => 1 , 'is_admin' => 0 ])){ 
					$users = Auth::user();
					$id = $users->id;
					$user = user::with(['userProfile' => function($q) use($id){
								$q->where('user_id',$id); 
							}])
							->where('email', $request->input('user_name'))
							->first();
					$success['user_id'] =  $user->id;
					$success['first_name'] =  $user->first_name;
					$success['last_name'] =  $user->last_name;
					$success['user_name'] =  $user->user_name;
					$success['email'] =  $user->email;		
					$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
					$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
					$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';	
					$bidds = ProductsBidder::where(['user_id' => $id])->get()->toArray();
					$success['buying_count'] = count($bidds);
					$sells = ProductSellerModel::where(['user_id' => $id])->get()->toArray();
					$success['selling_count'] = count($sells);		
					$success['social_id'] = null; 	
					$success['social_type'] = null; 			
					$success['token'] =  $user->createToken('MyApp')-> accessToken; 
					
					return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $success]); 
				}else{ 
						$user = user::with('userSocialProfile')
							->where('email', $request->input('user_name'))
							->first();


							// dd($this->getName($user->userSocialProfile->social_type));


						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							return response()->json(['code'=>0, 'errorCode'=>213, 'message'=>'Email is registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]);
							
						}else{
							
							return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please provide valid Username and Password.', 'data'=>(object)[]]);  
						}
					
				}
			}else{
				if(Auth::attempt(['user_name' => $request->input('user_name'), 'password' => $request->input('password'),'status' => 1 , 'is_admin' => 0])){ 
					$users = Auth::user();
					$id = $users->id;
					$user = user::with(['userProfile' => function($q) use($id){
								$q->where('user_id',$id); 
							}])
							->where('user_name', $request->input('user_name'))
							->first();
					$success['user_id'] =  $user->id;
					$success['first_name'] =  $user->first_name;
					$success['last_name'] =  $user->last_name;
					$success['user_name'] =  $user->user_name;
					$success['email'] =  $user->email;		
					$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
					$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
					$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';	
					$bidds = ProductsBidder::where(['user_id' => $id])->get()->toArray();
					$success['buying_count'] = count($bidds);
					$sells = ProductSellerModel::where(['user_id' => $id])->get()->toArray();
					$success['selling_count'] = count($sells);	
					$success['social_id'] = $user->userSocialProfile->social_id; 	
					$success['social_type'] = $user->userSocialProfile->social_type; 				
					$success['token'] =  $user->createToken('MyApp')-> accessToken; 
					
					return response()->json(['code'=>1, 'message'=>'success','data' => $success]); 
					
				}else{
						$user = user::with('userSocialProfile')
							->where('user_name', $request->input('user_name'))
							->first();
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							return response()->json(['code'=>0, 'errorCode'=>214, 'message'=>'Username is registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]);
							
						}else{
							
							return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please provide valid Username and Password.', 'data'=>(object)[]]);  
						}
				}
			}
           
        } 
        else{ 
            return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please provide Useraname and Password.', 'data'=>(object)[]]); 
        } 
	}

	/** 
     * Save 10 types of notification 
     * 
     * @return \Illuminate\Http\Response 
     */ 

	public function save_notification($id)
    {
        $Notification = [
            ['user_id'=> $id , 'notification_type' => '0' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '1' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '2' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '3' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '4' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '5' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '6' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '7' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '8' , 'status' => 1 ],
            ['user_id'=> $id , 'notification_type' => '9' , 'status' => 1 ]
        ];
        UserNotification::insert($Notification);
	}




	/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
		    'first_name' => 'required',
		    'last_name' => 'required',
            'user_name' => 'required|unique:users', 
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:6',  
        ]);
		$input = $request->all(); 
		if ($validator->fails()) {
                    $messages = $validator->messages();		
					if($messages->has('user_name')){
                        $user = user::with('userSocialProfile')
							->where('user_name', $input['user_name'])
							->first();
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							return response()->json(['code'=>0, 'errorCode'=>210, 'message'=>'Username is already registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount.' or use different username to register', 'data'=>(object)[]]);
							
						}else{
							return response()->json(['code'=>0, 'errorCode'=>202, 'message'=>'Username is already taken', 'data'=>(object)[]]); 
						}
					}
					elseif($messages->has('email')){ 
						 $user = user::with('userSocialProfile')
							->where('email', $input['email'])
							->first();
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							return response()->json(['code'=>0, 'errorCode'=>209, 'message'=>'Email is already registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount.' or use different email to register', 'data'=>(object)[]]);
							
						}else{
							
							return response()->json(['code'=>0, 'errorCode'=>201, 'message'=>'Email is already taken.', 'data'=>(object)[]]); 
						}
					}
					else { return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please provide valid inputs.', 'data'=>(object)[]]); }
				}
		
        $input['status'] =1; 
		$unix_timestamp = now()->timestamp;
		$input['created_at'] = $unix_timestamp;
		$user = User::create($input);
		if($user)
        { $this->save_notification($user->id);}
		$success['user_id'] =  $user->id;
		$success['first_name'] =  $user->first_name;
		$success['last_name'] =  $user->last_name;
		$success['user_name'] =  $user->user_name;
		$success['email'] =  $user->email;
		$success['buying_count'] = 0;
		$success['selling_count'] = 0;
			
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        
		return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success', 'data'=>$success]); 
    }
	/** 
     * Register Size api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register_size(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
		    'shoe_size' => 'required',
            'streetwear_size' => 'required', 
        ]);
		$user_id = Auth::id(); 
		if ($validator->fails()) { 
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$validator->errors(), 'data'=>(object)[]]);            
				}
		$input = $request->all(); 
		$user = User::find($user_id);
		if(!empty($user)){
			$user_profile = UserProfile::create([
				'user_id' => $user_id,  
				'shoe_size' => $input['shoe_size'],
				'streetwear_size' => $input['streetwear_size'],
			]);
			
			$success['user_id'] =  $user->id;
			$success['first_name'] =  $user->first_name;
			$success['last_name'] =  $user->last_name;
			$success['user_name'] =  $user->user_name;
			$success['email'] =  $user->email;
			$success['profile_pic_url'] =  isset($user_profile->profile_pic_url) ? url('/').$user_profile->profile_pic_url : '' ;				
			$success['shoe_size'] =  $user_profile->shoe_size;			
			$success['streetwear_size'] =  $user_profile->streetwear_size;
			
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success', 'data'=>$success]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>401, 'message'=>'User does not exist. Unauthorised user.', 'data'=>$success]); 
		}
    }
	/** 
     * Social Registeration api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function social_register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
		    'user_name' => 'required|unique:users', 
		    'email' => 'required|email|unique:users', 
            'social_id' => 'required|unique:user_social_profile',
            'social_type' => 'required',
		]);
		$input = $request->all(); 
		if ($validator->fails()) {
                    $messages = $validator->messages();		
					if($messages->has('user_name')){
						if(!empty($input['user_name'])){
                        $user = user::with('userSocialProfile')
							->where('user_name', $input['user_name'])
							->first();
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							$socialAccount1 = $this->getName($input['social_type']);
							if($socialAccount1 == $socialAccount){
								return response()->json(['code'=>0, 'errorCode'=>208, 'message'=>'Username is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
							}else{
								return response()->json(['code'=>0, 'errorCode'=>206, 'message'=>'Username is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
							}
						}else{
							$socialAccount = $this->getName($input['social_type']);
							return response()->json(['code'=>0, 'errorCode'=>204, 'message'=>'Username linked to your '.$socialAccount.' account is registered as normal user.', 'data'=>(object)[]]); 
						}
						}else{
							return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Username is required', 'data'=>(object)[]]); 
						
						}
					}
					elseif($messages->has('social_id')){ 
						if(!empty($input['social_id'])){
							 $user = UserSocialProfile::with('userSocialProfile')
								->where('social_id', $input['social_id'])
								->first();
							if(!empty($user)){
								$socialAccount = $this->getName($user->social_type);
								$socialAccount1 = $this->getName($input['social_type']);
								if($socialAccount1 == $socialAccount){
									return response()->json(['code'=>0, 'errorCode'=>207, 'message'=>'Social id is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
								}else{
									return response()->json(['code'=>0, 'errorCode'=>205, 'message'=>'Social id is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
								}
							}else{
								$socialAccount = $this->getName($input['social_type']);
								return response()->json(['code'=>0, 'errorCode'=>203, 'message'=>'Social id linked to your '.$socialAccount.' account is registered as normal user.', 'data'=>(object)[]]); 
						}}else{
								return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Social id is required', 'data'=>(object)[]]); 
							
							}
					}
					elseif($messages->has('email')){ 
					if(!empty($input['email'])){
						 $user = user::with('userSocialProfile')
							->where('email', $input['email'])
							->first();
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							$socialAccount1 = $this->getName($input['social_type']);
							if($socialAccount1 == $socialAccount){
								return response()->json(['code'=>0, 'errorCode'=>207, 'message'=>'Email is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
							}else{
								return response()->json(['code'=>0, 'errorCode'=>205, 'message'=>'Email is already registered using '.$socialAccount.', please sign in with '.$socialAccount.'', 'data'=>(object)[]]);
							}
						}else{
							$socialAccount = $this->getName($input['social_type']);
							return response()->json(['code'=>0, 'errorCode'=>203, 'message'=>'Email linked to your '.$socialAccount.' account is registered as normal user.', 'data'=>(object)[]]); 
					}}else{
							return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Email is required', 'data'=>(object)[]]); 
						
						}
					}
					else{ 
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please provide valid inputs.', 'data'=>(object)[]]); 
					}
				}
		
		
	    if(isset($input['user_name']) && isset($input['email'])){
			$user['password'] = '' ;
			$user['first_name'] = isset($input['first_name']) ? $input['first_name']: '' ;	
			$user['last_name'] = isset($input['last_name']) ? $input['last_name']: '' ;
			$user['user_name'] =  isset($input['user_name']) ? $input['user_name']: '' ;
			$user['email'] = isset($input['email']) ? $input['email'] : $input['user_name'].$input['social_id'].'@hypex1.com' ;	 
			$unix_timestamp = now()->timestamp;
			$user = User::create($user);
			if($user)
        	{ $this->save_notification($user->id);}
			$user_social = UserSocialProfile::create([
				'user_id' => $user->id,  
				'social_id' => $input['social_id'],
				'social_type' => $input['social_type'],
			]);
			
			$success['user_id'] =  $user->id;
			$success['first_name'] =  $user->first_name;
			$success['last_name'] =  $user->last_name;
			$success['user_name'] =  $user->user_name;
			if (strpos($user->email, 'hypex1.com') !== false) {
			   $success['email'] =  '';
			}else { $success['email'] =  $user->email;; }
			$success['social_id'] =  $user_social->social_id;			
			$success['social_type'] =  $user_social->social_type;	
			$success['buying_count'] = 0;
			$success['selling_count'] = 0;
			$success['token'] =  $user->createToken('MyApp')-> accessToken; 
			
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success', 'data'=>$success]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please make sure you have provided Username and Email.', 'data'=>(object)[]]);
		}
    }
	/** 
     * Social login api 
     *  
     * @return \Illuminate\Http\Response 
 //     */ 
	public function social_login(Request $request){

	    $password = '';
	    $s_id = $request->input('social_id');
		$social_type = $request->input('social_type'); 
		 
	
			$user = user::whereHas('userSocialProfile',function($q) use ($s_id,$social_type ){
								$q->where('social_id','=',$s_id);
							})->with(['userProfile','userSocialProfile'])->first(); 
		
		if(!empty($user)){

			$success['user_id'] =  $user->id;
			$success['first_name'] =  $user->first_name;
			$success['last_name'] =  $user->last_name;
			$success['user_name'] =  $user->user_name;
			$success['email'] =  $user->email;		
			$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
			$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
			$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';	
			$bidds = ProductsBidder::where(['user_id' => $user->id])->get()->toArray();
			$success['buying_count'] = count($bidds);
			$sells = ProductSellerModel::where(['user_id' => $user->id])->get()->toArray();
			$success['selling_count'] = count($sells);	
			$success['social_id'] = $s_id;	
			$success['social_type'] = $social_type;						
			$success['token'] =  $user->createToken('MyApp')-> accessToken; 
						
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $success]); 

		}else{
		return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'User does not exists.', 'data'=>(object)[]]); 
		}
	}

	  

    public function social_login1(Request $request){ 

     	//dd($request->social_id); 
	
		$password = '';
		$s_id = $request->input('social_id');
		$social_type = $request->input('social_type'); 
		if(!empty($request->input('user_name'))){ 
			if(filter_var($request->input('user_name'), FILTER_VALIDATE_EMAIL)) {
				if(Auth::attempt(['email' => $request->input('user_name'), 'password' => $password])){
					$user = user::whereHas('userSocialProfile',function($q) use ($s_id,$social_type ){
								$q->where('social_id','=',$s_id)->where('social_type','=',$social_type); 
							})->with(['userProfile','userSocialProfile'])
							->where('email', $request->input('user_name'))
							->first();
					if(!empty($user)){
						$success['user_id'] =  $user->id;
						$success['first_name'] =  $user->first_name;
						$success['last_name'] =  $user->last_name;
						$success['user_name'] =  $user->user_name;
						$success['email'] =  $user->email;		
						$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
						$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
						$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';	
						$bidds = ProductsBidder::where(['user_id' => $user->id])->get()->toArray();
						$success['buying_count'] = count($bidds);
						$sells = ProductSellerModel::where(['user_id' => $user->id])->get()->toArray();
						$success['selling_count'] = count($sells);						
						$success['token'] =  $user->createToken('MyApp')-> accessToken; 
						
						return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $success]); 
					}else{
						$user = user::with('userSocialProfile')
							->where('email', $request->input('user_name'))
							->first();
						$socialAccount = $this->getName($user->userSocialProfile->social_type);
						return response()->json(['code'=>0, 'errorCode'=>215, 'message'=>'Email is registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]); 
					}
				}else{
					if (User::where('email', '=', $request->input('user_name'))->exists()) {
						$socialAccount = $this->getName($social_type);
						return response()->json(['code'=>0, 'errorCode'=>211, 'message'=>'Email linked to your '.$socialAccount.' account is registered as normal user, please login with your credentials', 'data'=>(object)[]]); 
					}else{
						
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No user exist with this email', 'data'=>(object)[]]);
					}
				}
			}else{
				if(Auth::attempt(['user_name' => $request->input('user_name'), 'password' => $password])){
					$user = user::whereHas('userSocialProfile',function($q) use ($s_id,$social_type ){
								$q->where('social_id','=',$s_id)->where('social_type','=',$social_type); 
							})->with(['userProfile','userSocialProfile'])
							->where('user_name', $request->input('user_name'))
							->first();
					if(!empty($user)){
						$success['user_id'] =  $user->id;
						$success['first_name'] =  $user->first_name;
						$success['last_name'] =  $user->last_name;
						$success['user_name'] =  $user->user_name;
						$success['email'] =  $user->email;		
						$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
						$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
						$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';		
						$bidds = ProductsBidder::where(['user_id' => $user->id])->get()->toArray();
						$success['buying_count'] = count($bidds);
						$sells = ProductSellerModel::where(['user_id' => $user->id])->get()->toArray();
						$success['selling_count'] = count($sells);
						$success['token'] =  $user->createToken('MyApp')-> accessToken; 
						
						return response()->json(['code'=>1, 'message'=>'success','data' => $success]); 
					}else{
						$user = user::with('userSocialProfile')
							->where('user_name', $request->input('user_name'))
							->first();
						$socialAccount = $this->getName($user->userSocialProfile->social_type); 
						return response()->json(['code'=>0, 'errorCode'=>216, 'message'=>'Username is registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]); 
					}
					
				}else{
					if (User::where('user_name', '=', $request->input('user_name'))->exists()) {
						
						$socialAccount = $this->getName($social_type); 
						return response()->json(['code'=>0, 'errorCode'=>212, 'message'=>'Username linked to your '.$socialAccount.' account is registered as normal user, please login with your credentials', 'data'=>(object)[]]); 
					}else{
						
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No user exist with this email.', 'data'=>(object)[]]);
					}
				}
			}
           
        } 
        else{ 
            return response()->json(['code'=>0, 'message'=>'Please provide valid Username.', 'data'=>(object)[]]); 
        } 
	}
	

	/// new social login

	public function social_login2(Request $request){ 
	//dd($request->social_type); 
		$password = '';
		$s_id = $request->input('social_id');
		$social_type = $request->input('social_type'); 
		$users = user::whereHas('userSocialProfile',function($q) use ($s_id){
			$q->where('social_id','=',$s_id); 
		})->with(['userProfile','userSocialProfile'])
		->first(); 
		if(!empty($users->user_name)){ 
			if(filter_var($users->email, FILTER_VALIDATE_EMAIL)) {
				if(Auth::attempt(['email' => $users->email, 'password' => $password])){
					$user = user::whereHas('userSocialProfile',function($q) use ($s_id,$social_type ){
								$q->where('social_id','=',$s_id)->where('social_type','=',$social_type); 
							})->with(['userProfile','userSocialProfile'])
							->where('email', $users->email)
							->first();
					if(!empty($user)){
						$success['user_id'] =  $user->id;
						$success['social_id'] =  $user->userSocialProfile->social_id;
						$success['social_type'] =  $user->userSocialProfile->social_type;
						$success['first_name'] =  $user->first_name;
						$success['last_name'] =  $user->last_name;
						$success['user_name'] =  $user->user_name;
						$success['email'] =  $user->email;		
						$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
						$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
						$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';	
						$bidds = ProductsBidder::where(['user_id' => $user->id])->get()->toArray();
						$success['buying_count'] = count($bidds);
						$sells = ProductSellerModel::where(['user_id' => $user->id])->get()->toArray();
						$success['selling_count'] = count($sells);						
						$success['token'] =  $user->createToken('MyApp')-> accessToken; 
						
						return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $success]); 
					}else{
						$user = user::with('userSocialProfile')
							->where('email', $users->email)
							->first();
						$socialAccount = $this->getName($user->userSocialProfile->social_type);
						return response()->json(['code'=>0, 'errorCode'=>215, 'message'=>'Registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]); 
					}
				}else{
					if (User::where('email', '=', $users->email)->exists()) {
						$socialAccount = $this->getName($social_type);
						return response()->json(['code'=>0, 'errorCode'=>211, 'message'=>'Email linked to your '.$socialAccount.' account is registered as normal user, please login with your credentials', 'data'=>(object)[]]); 
					}else{
						
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No user exist with this email', 'data'=>(object)[]]);
					}
				}
			}else{
				if(Auth::attempt(['user_name' => $users->user_name, 'password' => $password])){
					$user = user::whereHas('userSocialProfile',function($q) use ($s_id,$social_type ){
								$q->where('social_id','=',$s_id)->where('social_type','=',$social_type); 
							})->with(['userProfile','userSocialProfile'])
							->where('user_name', $users->user_name)
							->first();
					if(!empty($user)){
						$success['user_id'] =  $user->id;
						$success['social_id'] =  $user->userSocialProfile->social_id;
						$success['social_type'] =  $user->userSocialProfile->social_type;
						$success['first_name'] =  $user->first_name;
						$success['last_name'] =  $user->last_name;
						$success['user_name'] =  $user->user_name;
						$success['email'] =  $user->email;		
						$success['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
						$success['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
						$success['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';		
						$bidds = ProductsBidder::where(['user_id' => $user->id])->get()->toArray();
						$success['buying_count'] = count($bidds);
						$sells = ProductSellerModel::where(['user_id' => $user->id])->get()->toArray();
						$success['selling_count'] = count($sells);
						$success['token'] =  $user->createToken('MyApp')-> accessToken; 
						
						return response()->json(['code'=>1, 'message'=>'success','data' => $success]); 
					}else{
						$user = user::with('userSocialProfile')
							->where('user_name', $users->user_name)
							->first();
						$socialAccount = $this->getName($user->userSocialProfile->social_type); 
						return response()->json(['code'=>0, 'errorCode'=>216, 'message'=>'Registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]); 
					}
					
				}else{
					if (User::where('user_name', '=', $users->user_name)->exists()) {
						
						$socialAccount = $this->getName($social_type); 
						return response()->json(['code'=>0, 'errorCode'=>212, 'message'=>'Username linked to your '.$socialAccount.' account is registered as normal user, please login with your credentials', 'data'=>(object)[]]); 
					}else{
						
						return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No user exist with this email', 'data'=>(object)[]]);
					}
				}
			}
           
        } 
        else{ 
            return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'User does not exists', 'data'=>(object)[]]); 
        } 
    }

	/////// new social login
	
	/** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function myProfile(){ //dd("hello");
		$id = Auth::id(); //dd($id);
		if($id != NULL || $id != ''){
			$user = user::with(['userProfile' => function($q) use($id){
								$q->where('user_id',$id); 
							}])
							->where('id', $id)
							->first();
			if($user){
				$data['user_id'] = $user->id;
				$data['first_name'] = $user->first_name;
				$data['last_name'] = $user->last_name;
				$data['email'] = $user->email;
				$data['user_name'] = $user->user_name;
				$data['profile_pic_url'] = isset($user->userProfile->profile_pic_url) ? url('/').$user->userProfile->profile_pic_url : '' ;		
				$data['shoe_size'] =  isset($user->userProfile->shoe_size) ? $user->userProfile->shoe_size : '';			
				$data['streetwear_size'] =  isset($user->userProfile->streetwear_size) ? $user->userProfile->streetwear_size : '';				
				$bidds = ProductsBidder::where(['user_id' => $id])->get()->toArray();
				$data['buying_count'] = count($bidds);
				$sells = ProductSellerModel::where(['user_id' => $id])->get()->toArray();
				$data['selling_count'] = count($sells);
				$user_social = userSocialProfile::whereHas('user',function($q) use ($id){
								$q->where('user_id','=',$id);
							})->first(); 
				if(!empty($user_social)){
						$data['social_id'] = $user->userSocialProfile->social_id; 	
						$data['social_type'] = $user->userSocialProfile->social_type; 
				}else{
						$data['social_id'] = null; 	
						$data['social_type'] = null; 
				}
			
				$data['token'] =  $user->createToken('MyApp')-> accessToken; 
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success', 'data'=>$data]); 
			}else {
				return response()->json(['code'=>0, 'errorCode'=>401, 'message'=>'User Id does not exist', 'data'=>(object)[]]);
			}
			
		}else{
			
			return response()->json(['code'=>0, 'errorCode'=>401, 'message'=>'Please Provide userID.', 'data'=>(object)[]]);
		}
	}
	/** 
     * changePassword api 
     * 
     * @return \Illuminate\Http\Response 
     */
	public function changePassword(Request $request)
    {   //dd($request);
        $id = Auth::id();
		$validator = Validator::make($request->all(), [ 
		    'old_password' => 'required',
            'new_password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
                    $messages = $validator->messages();		
					return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$validator->errors()->first() ,'data'=>(object)[]]);
				}

        $user = user::find($id);
        if (!\Hash::check($request->input('old_password'), $user->password)) {
            return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Sorry, your old password is incorrect.','data'=>(object)[]]); 
			
        } else {
            $user->password = $request->input('new_password');
            $user->save();

            if ($user->id) {
                return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Password has been updated successfuly.','data'=>(object)[]]); 
            } else {
                return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Sorry, some error found. please try again after sometimes.','data'=>(object)[]]); 
                
            }
        }
    }
	public function sendForgotPasswordEmail(Request $request){
		
		$request->validate([
            'email' => 'required|string|email',
        ]);
		
		$emailID = $request->email;
		$user = user::with('userSocialProfile')
							->where('email', $emailID)
							->first();
		if(!empty($user->userSocialProfile)){
            $socialAccount = $this->getName($user->userSocialProfile->social_type); 
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'This user is registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount. '', 'data'=>(object)[]]); 
					
		}else{
			if (!$user)
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No account registered with provided email.','data'=>(object)[]]);
			$insert_arary = [
					'email' => $user->email,
					'user_id' => $user->id,
					'token' => str_random(60)	
				];
			$passwordReset = PasswordReset::updateOrCreate($insert_arary);
			
			try{
				if ($user && $passwordReset)
				$user->notify(
					new PasswordResetRequest($passwordReset->token)
				);
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Password Reset Email Sent and is Valid for next 30 minutes.','data'=>(object)[]]);
			} catch(\Swift_TransportException $ex){
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Opps Something went wrong.','data'=>(object)[]]);
			}
		}
	}
	
	/*** Logout and session expire ***/
	public function logout(Request $request){
		
		if (Auth::check()) {
			Auth::user()->AauthAcessToken()->delete();
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'User logged out successfuly.','data'=>(object)[]]);
		}
		
	}
	/**
     * Show the application updateProfile.
     *
     * @return \Illuminate\Http\Response
     */
    
	public function updateProfile(Request $request){
		$id = Auth::id();
		if($id != NULL || $id != ''){
			$validator = Validator::make($request->all(), [ 
				'first_name' => 'required|string|min:4|max:100',
				'last_name' => 'required|string|min:4|max:100',
				'email' => 'required|max:150|email|unique:users,email,'.$id,
				'phone' => 'nullable|numeric|digits_between:6,12',
				'city' => 'nullable|string|min:4|max:100',
				'state' => 'nullable|string|min:4|max:100',
				'country' => 'nullable|string|min:4|max:100',
				'postal_code' => 'nullable|numeric|digits_between:4,8',
				'shoe_size' => 'nullable',
				'streetwear_size' => 'nullable',
				'profile_pic_url' => 'nullable'
			]);
			$validated = $request; 
			$delete = $validated['is_delete'];
			$user = Auth::user();
			if ($validator->fails()) { 
                    $messages = $validator->messages();dd($messages);
					if($messages->has('email')){ 
						 $user = user::with('userSocialProfile')
							->where('email', $validated['email'])
							->first(); 
						if(!empty($user->userSocialProfile)){
							$socialAccount = $this->getName($user->userSocialProfile->social_type);
							return response()->json(['code'=>0, 'errorCode'=>209, 'message'=>'Email is already registered using '.$socialAccount.' Sign In, please sign in with '.$socialAccount.' or use different email to register', 'data'=>(object)[]]);
							
						}else{
							
							return response()->json(['code'=>0, 'errorCode'=>201, 'message'=>'Email is already taken.', 'data'=>(object)[]]); 
						}
					}
					else { return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>$validator->errors()->first(), 'data'=>(object)[]]); }
				}
			$user = user::with(['userProfile' => function($q) use($id){
								$q->where('user_id',$id); 
							}])
							->where('id', $id)    
							->first(); 
			$success['user_id'] =  $user->id;
			$success['first_name'] = $user->first_name = $validated['first_name'];
			$success['last_name'] = $user->last_name = $validated['last_name'];
			$success['user_name'] = $user->user_name;
			$success['email'] = $user->email = $validated['email']; 
			$user_social = userSocialProfile::whereHas('user',function($q) use ($id){
								$q->where('user_id','=',$id);
							})->first(); 
				//print_r($user_social);	die;
					if(!empty($user_social)){

						$success['social_id'] = $user->userSocialProfile->social_id; 	
						$success['social_type'] = $user->userSocialProfile->social_type; 
					}else{
						$success['social_id'] = null; 	
						$success['social_type'] = null; 
					}
//dd($delete);
			if($delete == 1){
				if($user->userProfile){
					$success['shoe_size'] =  $user->userProfile->shoe_size = $validated['shoe_size'];
					$success['streetwear_size'] = $user->userProfile->streetwear_size = $validated['streetwear_size'];
					$success['profile_pic_url'] = $user->userProfile->profile_pic_url = null;
					$user->userProfile->save();
				}
			}else{ //dd($user->userProfile);
				if($user->userProfile){
					$success['shoe_size'] =  $user->userProfile->shoe_size = $validated['shoe_size'];
					$success['streetwear_size'] = $user->userProfile->streetwear_size = $validated['streetwear_size']; 
					if(!empty($validated['profile_pic_url'])){
     //                $img = base64_decode($validated['profile_pic_url']); 
					// $ext = pathinfo($img,PATHINFO_EXTENSION);
					// $imageName = time().$user->first_name.'.'.$ext; 
					// $path = public_path() . '/profile_img/'. $imageName;
					// $data = file_get_contents($img);
					// file_put_contents($path, $data);
					// $url = $user->userProfile->profile_pic_url = '/profile_img/'. $imageName;
					// $success['profile_pic_url'] = url('/').$url;


						$str = $validated['profile_pic_url'];
						$ext=substr($str, 11, strpos($str, ';')-11);
						$png_url = time().$user->first_name.'.'.$ext; //dd($png_url);
						$path = public_path() . "/profile_img/" . $png_url;
						$img = $validated['profile_pic_url'];
						$img = substr($img, strpos($img, ",")+1);
						$data = base64_decode($img);
						$upload = file_put_contents($path, $data);
						$success['profile_pic_url'] = url('/').'/public/profile_img/'. $png_url;
					}else{
						$success['profile_pic_url'] = NULL;
					}
					
					$user->userProfile->save();
				}else{ 
					if(!empty($validated['profile_pic_url'])){

					// $img = base64_decode($validated['profile_pic_url']);
					// $ext = pathinfo($img,PATHINFO_EXTENSION);
					// $imageName = $user->user_name.'.'.$ext; 
					// $path = public_path() . '/profile_img/'. $imageName;
					// $data = file_get_contents($img);
					// file_put_contents($path, $data);
						$str = $validated['profile_pic_url'];
						$ext=substr($str, 11, strpos($str, ';')-11);
						$png_url = time().$user->first_name.'.'.$ext;
						$path = public_path() . "/profile_img/" . $png_url;
						$img = $validated['profile_pic_url'];
						$img = substr($img, strpos($img, ",")+1);
						$data = base64_decode($img);
						$upload = file_put_contents($path, $data);
					    $user_profile = UserProfile::create([
						'user_id' => $id,  
						'shoe_size' => $validated['shoe_size'],
						'streetwear_size' => $validated['streetwear_size'],
						'profile_pic_url' => '/public/profile_img/'. $png_url
					]);
					}else{
						$user_profile = UserProfile::create([
						'user_id' => $id,  
						'shoe_size' => $validated['shoe_size'],
						'streetwear_size' => $validated['streetwear_size'],
						'profile_pic_url' => NULL
					]);
					}
					    $str = $validated['profile_pic_url'];
						$ext=substr($str, 11, strpos($str, ';')-11);
						
					$user_profile = UserProfile::create([
						'user_id' => $id,  
						'shoe_size' => $validated['shoe_size'],
						'streetwear_size' => $validated['streetwear_size'],
						'profile_pic_url' => '/public/profile_img/'. time().$user->first_name.'.'.$ext
					]);

					if($user_profile->id){
						$url  = '/profile_img/'. time().$user->first_name.'.'.$ext;
						$success['profile_pic_url'] = url('/').$url;
						$success['shoe_size'] =  $validated['shoe_size'];
						$success['streetwear_size'] = $validated['streetwear_size'];
						
					}
				}
			}

			$bidds = ProductsBidder::where(['user_id' => $id])->get()->toArray();
			$success['buying_count'] = count($bidds);
			$sells = ProductSellerModel::where(['user_id' => $id])->get()->toArray();
			$success['selling_count'] = count($sells);
			$success['token'] =  $user->createToken('MyApp')-> accessToken; 
			if($user->save()){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Profile updated successfully.','data'=>$success]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Technical Error Occurred.','data'=>(object)[]]);
			}
			
			
		}else{
			
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Please login.','data'=>(object)[]]);
		}
	}
	/**** common function ****/
	function getName($name){
		if($name == 'F'){ return 'Facebook'; }
		elseif($name == 'T'){ return 'Twitter'; }
		elseif($name == 'G'){ return 'Google'; }
		elseif($name == 'W'){ return 'WeChat'; }
		else {}
		
		
	}
	
	/****** User Payout infos ****/
	public function addPayoutEmailAddress(Request $request){
		$userID = Auth::id();
		$request = $request->all();

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
		$data['id']= $payoutId;
		if(!empty($data)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Payout email saved successfully.','data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>(object)[]]);
		}
	}
    
	public function getPayoutEmailAddress(){
		$userID = Auth::id();
		$getEmails = PayoutInfoModel::where([['user_id','=',$userID],['status', '=', '1']])->get()->toArray();
			 
		if(!empty($getEmails)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Payout email retrieved successfully.','data'=>$getEmails]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some Error Occurred.','data'=>$getEmails]);
		}
	}
	
	/***** User setting info ****/
	public function getSettingsInfo(){
		$userID = Auth::id();
		$data = array();
		$shipping_address  = UsersShippingAddressModel::where([['user_id','=',$userID],['status', '=', '1'],['default', '=', '1']])->orderBy('created_at', 'desc')->get()->first();
		if(!empty($shipping_address)){
				 $shipping_address['country'] = $this->getCountry($shipping_address['country']);
				 $shipping_address['province'] = $this->getProvince($shipping_address['province']);
				
			}
		$data['shipping_address']  = isset($shipping_address) ? $shipping_address : (object)[];
		
		$datareturning_address  = UsersBillingAddressModel::where([['user_id','=',$userID],['status', '=', '1'],['default', '=', '1']])->orderBy('created_at', 'desc')->get()->first();
		
		if(!empty($datareturning_address)){
				 $datareturning_address['country'] = $this->getCountry($datareturning_address['country']);
				 $datareturning_address['province'] = $this->getProvince($datareturning_address['province']);
				
			}
		$data['returning_address']  = isset($datareturning_address) ? $datareturning_address : (object)[];
		$payout_address = PayoutInfoModel::where([['user_id','=',$userID],['status', '=', '1']])->get()->first();
		
		$data['payout_address'] = isset($payout_address) ? $payout_address : (object)[];
		
		$card = CardsModel::select('id','stripe_details','default')->where([['user_id','=',$userID],['status', '=', '1'],['default', '=', '1']])->orderBy('created_at', 'desc')->get()->first();
		if(!empty($card)){
				 $cardDetail['id'] = $card['id'];
				 $customer = json_decode($card['stripe_details']); 					 
				 $cardDetail['customer_id'] = $customer[0]->customer;
				 $cardDetail['name'] = $customer[0]->name; 
				 $cardDetail['last4'] = $customer[0]->last4; 
				 $cardDetail['exp_month'] = $customer[0]->exp_month; 
				 $cardDetail['exp_year'] = $customer[0]->exp_year; 
				 $cardDetail['default'] = $card['default'];
				 
			}
		$data['user_cards'] = isset($cardDetail) ? $cardDetail : (object)[];
		if(!empty($data)){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Setting Info retrieved successfully.','data'=>$data]);
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.','data'=>$data]);
		}
	}
	function getCountry($abb){
		$country = CountriesModel::select('name')->where('abbreviation',$abb)->get()->first();
		return $country['name']; 
	}
	function getProvince($abb){
		$province = ProvincesModel::select('name')->where('abbreviation',$abb)->get()->first();
		return $province['name']; 
	}
	
	/**
	 * getBuyingProducts section.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function getBuyingProducts(){
        
		$id = Auth::id();
		if($id != NULL || $id != ''){
			$data = array();
			
			$openbidds = ProductsBidder::with(['product','size'])->where(['status' => 1, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray();
			if(!empty($openbidds)){
				foreach($openbidds as $openbid){
					$bids['bid_id'] = $openbid['id'];
					$bids['product_id'] = $openbid['product']['id'];
					$bids['price'] = 'CA$'.$openbid['bid_price'];
					$bids['date'] = $openbid['created_at'];
					$bids['size'] = WebHelper::getSize($openbid['size_id'],$openbid['product']['id']);
					$bids['product_name'] = $openbid['product']['product_name'];
					$bids['brand'] = WebHelper::getBrandNameById($openbid['product']['brand_id']);
					$images = $openbid['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$bids['product_images'] = url('/').'/'.current($prodImages);
					$openBidsData[] =$bids;
				}
				
			}
			$data['openbids'] = isset($openBidsData) ? $openBidsData : array() ;	
			$progressbidds = ProductsBidder::with(['product','size'])->where(['status' => 0, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray();
			if(!empty($progressbidds)){
				foreach($progressbidds as $progressbidd){
					$bid['bid_id'] = $progressbidd['id'];
					$bid['product_id'] = $progressbidd['product']['id'];
					$bid['price'] = 'CA$'.$progressbidd['bid_price'];
					$bid['date'] = $progressbidd['created_at'];
					$bid['size'] = WebHelper::getSize($progressbidd['size_id'],$progressbidd['product']['id']);
					$bid['product_name'] = $progressbidd['product']['product_name'];
					$bid['brand'] = WebHelper::getBrandNameById($progressbidd['product']['brand_id']);
					$images = $progressbidd['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$bid['product_images'] = url('/').'/'.current($prodImages);
					$progressBiddsData[] =$bid;
				}
				
			}
			$data['progressbids'] = isset($progressBiddsData) ? $progressBiddsData : array() ;	
			$buyinghistory = OrdersModel::with(['seller','product','procategory','brand'])->where(['user_id' => $id])->orderby('id', 'desc')->get()->toArray(); 
			if(!empty($buyinghistory)){
				foreach($buyinghistory as $history){
					$historyi['order_id'] = $history['id'];
					$historyi['product_id'] = $history['product_id'];
					$historyi['order_ref_number'] = $history['order_ref_number'];
					$historyi['date'] = $history['created_at'];
					$historyi['size'] = WebHelper::getSize($history['product_size_id'],$history['product_id']);
					$historyi['price'] = 'CA$'.$history['total_price'];
					$historyi['product_name'] = $history['product']['product_name'];
					$historyi['brand'] = WebHelper::getBrandNameById($history['product']['brand_id']);
					$images = $history['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$historyi['product_images'] = url('/').'/'.current($prodImages);
					$buyinghistoryData[] =$historyi;
				}
				
			}
			$data['buyinghistory'] = isset($buyinghistoryData) ? $buyinghistoryData : array() ;	
			if(!empty($data)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Data retrieved successfully.','data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.','data'=>$data]);
			}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.','data'=>$data]);
		}

	}
	
	/**
	 * getSellingProducts section.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function getSellingProducts(){
        
		$id = Auth::id();
		if($id != NULL || $id != ''){
			$data = array();
			
			$openbidds = ProductSellerModel::with(['product','size'])->where(['status' => 1, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray();
			if(!empty($openbidds)){
				foreach($openbidds as $openbid){
					$bids['sell_id'] = $openbid['id'];
					$bids['product_id'] = $openbid['product']['id'];
					$bids['price'] = 'CA$'.$openbid['ask_price'];
					$bids['date'] = $openbid['created_at'];
					$bids['size'] = WebHelper::getSize($openbid['size_id'],$openbid['product']['id']);
					$bids['product_name'] = $openbid['product']['product_name'];
					$bids['brand'] = WebHelper::getBrandNameById($openbid['product']['brand_id']);
					$images = $openbid['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$bids['product_images'] = url('/').'/'.current($prodImages);
					$openBidsData[] =$bids;
				}
				
			}
			$data['openSells'] = isset($openBidsData) ? $openBidsData : array() ;	
			$progressbidds = ProductSellerModel::with(['product','size'])->where(['status' => 0, 'user_id' => $id])->orderby('id', 'desc')->get()->toArray(); 
			 if(!empty($progressbidds)){
				foreach($progressbidds as $progressbidd){
					$bid['sell_id'] = $progressbidd['id'];
					$bid['product_id'] = $progressbidd['product']['id'];
					$bid['price'] = 'CA$'.$progressbidd['ask_price'];
					$bid['date'] = $progressbidd['created_at'];
					$bid['size'] = WebHelper::getSize($progressbidd['size_id'],$progressbidd['product']['id']);
					$bid['product_name'] = $progressbidd['product']['product_name'];
					$bid['brand'] = WebHelper::getBrandNameById($progressbidd['product']['brand_id']);
					$images = $progressbidd['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$bid['product_images'] = url('/').'/'.current($prodImages);
					$progressBiddsData[] =$bid;
				}
				
			}
			$data['progressSells'] = isset($progressBiddsData) ? $progressBiddsData : array() ;	
			$sellinghistory = OrdersModel::with(['seller','product','procategory','brand'])->where(['user_id' => $id])->orderby('id', 'desc')->get()->toArray(); 
			//print_r($sellinghistory); die;
			if(!empty($sellinghistory)){
				foreach($sellinghistory as $history){
					$historyi['order_id'] = $history['id'];
					$historyi['product_id'] = $history['product_id'];
					$historyi['order_ref_number'] = $history['order_ref_number'];
					$historyi['date'] = $history['created_at'];
					$historyi['size'] = WebHelper::getSize($history['product_size_id'],$history['product_id']);
					$historyi['price'] = 'CA$'.$history['total_price'];
					$historyi['product_name'] = $history['product']['product_name'];
					$historyi['brand'] = WebHelper::getBrandNameById($history['product']['brand_id']);
					$images = $history['product']['product_images'];
					$file = $images;
					$prodImages = explode(',',$file);
					$historyi['product_images'] = url('/').'/'.current($prodImages);
					$sellinghistoryData[] =$historyi;
				}
				
			}
			$data['sellinghistory'] =  isset($sellinghistoryData) ? $sellinghistoryData : array() ;	
			if(!empty($data)){
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Data retrieved successfully.','data'=>$data]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.','data'=>$data]);
			}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data.','data'=>$data]);
		}

	}
	
}