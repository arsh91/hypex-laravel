<?php

namespace App\Http\Controllers\v1\website;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Models\PasswordReset;
use DB;
use App\Models\ProductsModel;
use App\Models\BrandsModel;
use App\Models\CategoriesModel;
use App\APIModels\UserNotification;
use App;


class HomeController extends Controller
{
    
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(Auth::check() && Auth::user()->is_admin == 1){
			return redirect('admin');
		}
		$productDetails = ProductsModel::with(['productCategory','productBrandType',
		'productBrand','productSizeTypes','productSizes'])->limit(6)->get()->toArray();

		//dump($productDetails);
		$productCategories = '';
		$data = array();
		$brand_ids = [];
        $category_ids = [];
        $category_details = [];
        $brand_ids = BrandsModel::where(['status'=> 1])->pluck('id')->toArray();
        $category_ids = CategoriesModel::where(['status'=> 1])->pluck('id')->toArray();


        //Fetch category details to list it on home page
        $category_details = CategoriesModel::where(['status'=> 1])->limit(6)->get()->toArray();
		//dump($category_details);


		$data['relatedProducts'] = ProductsModel::with(['productCategory','productBrandType',
		'productBrand','productSizeTypes'])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status'=>'1','vip_status' => '0','trending'=>'1'])->orderBy('updated_at','DESC')->limit(10)->get()->toArray();
        
        $data['recommendedProducts'] = ProductsModel::with(['productCategory','productBrandType',
		'productBrand','productSizeTypes'])->whereIn('brand_id', $brand_ids )->whereIn('category_id',$category_ids)->where(['status'=>'1','vip_status' => '0','trending'=>'0'])->orderBy('updated_at','DESC')->limit(10)->get()->toArray();

        $data['title']="Home";
		$data['top_products'] = $productDetails;
		$data['trending_products'] = $productDetails;
		$data['lowest_offer_products'] = $productDetails;
		$data['highestbidproducts'] = $productDetails;
		$data['upcoming_releasing_products'] = $productDetails;
		$data['category_details'] = $category_details; //set the category object

        return view('website.home', $data);
    }
    
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
	
	
	public function register(RegisterRequest $request)
    {
		$validated = $request->validated();
		$unix_timestamp = now()->timestamp;
		$email = $validated['email'];
		$password = $validated['password'];
		
	    $new_user =  User::create([
			'first_name' => $validated['first_name'],
			'last_name' => $validated['last_name'],
			'user_name' => $validated['user_name'],
			'email' => $email,
			'password' => $password,
			'created_at' => $unix_timestamp,
			'status' => 1
        ]);
        if($new_user)
        { $this->save_notification($new_user->id);}
		
		if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => 1])){
            return redirect()->home()->with('success',__('home.Registered Successfully'));
        }else{
			return back()->withErrors(__('home.Authentication Failed !!'));
		}
		
		
		
	}
	
	
	public function login(LoginRequest $request)
	{
		$validated = $request->validated();
		
		// Only login if user status is 1
		$validated['status'] = 1;
		$validated['is_admin'] = 0;
		
		if (Auth::attempt($validated,true)) {
			if($request->session()->has('old_url')) {
                $refererURL = $request->session()->get('old_url');
                $subs = explode('/',$refererURL);
                $subsrl = end($subs);
                if($subsrl == 'subscriptioncheck'){
                    return redirect()->home()->with('success',__('home.Logged In Successfully !!'));
                }else{
                    return redirect($refererURL);
                }
			}else{

            return redirect()->home()->with('success',__('home.Logged In Successfully !!'));
			}
            
        }else{
			return back()->withErrors(__('home.Authentication Failed !!'))->withInput(Input::except('password'))->with(array('form'=>'login'));
		}
	}
	
	
	public function logout(Request $request){
		
		if (!Auth::check()) {
			return redirect('/');
		}

		Auth::logout();
        $locale = App::getLocale();
		$request->session()->flush();
        $request->session()->put('locale', $locale);

		return redirect()->home()->with('success',__('home.Logged Out Successfully !!'));
	}
	
	
	public function sendForgotPasswordEmail(Request $request){
		
		$request->validate([
            'email' => 'required|string|email',
        ]);
		
		$emailID = $request->email;
		
        $user = User::where('email', $emailID)->first();
		
		if (!$user)
            return back()->withErrors(['No account registered with provided email.'])->withInput(Input::except('password'));
		
		
		$passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'user_id' => $user->id,
                'token' => str_random(60)
             ]
        );
		
		if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
			
		return redirect()->home()->with('success','Password Reset Email Sent and is Valid for next 30 minutes !!');
		
	}
	
	public function checkResetLinkValidity($token){
		
		$passwordReset = PasswordReset::where('token', $token)->first();
		
        if (!$passwordReset)
            return view('password-error')->withErrors(['INVALID Password Reset Link.']);
		
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(30)->isPast()) {
            $passwordReset->delete();
            return view('password-error')->withErrors(['Password Reset Link Expired, Kindly RESET your password again !!']);
        }
		
        return view('reset-password',array('token'=>$token));
		
	}
	

	
	public function changePassword(Request $request)
    {
        Session::flash('pasword_form', TRUE);
        $messages = array(
            'old_password.required' => 'Please enter old password',
            'new_password.required' => 'Please enter new password',
            'new_password_confirmation.required' => 'Please enter valid new password confirmation',
        );

        $rules = array(
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|min:6|same:new_password'
        );

        $this->validate($request, $rules, $messages);

        $user = Auth::user();

        if (!\Hash::check($request->input('old_password'), $user->password)) {
            $this->flash('danger', 'Sorry, your old password is not incorrect!!');
            return redirect(url()->previous());
        } else {
            $user->password = bcrypt($request->input('new_password'));
            $user->save();

            if ($user->id) {
                $this->flash('success', 'User password has been successfully update.');
                return redirect(url()->previous());
            } else {
                $this->flash('danger', 'Sorry, some error found. please try again after sometimes.');
                return redirect(url()->previous());
            }
        }
    }
	
	
	
	public function resetPassword(Request $request)
    {
		$request->validate([
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
		
        $passwordReset = PasswordReset::where([['token', $request->token]])->first();
		
		if (!$passwordReset)
            return view('password-error')->withErrors(['INVALID Password Reset Link.']);
		
		$user = User::where('email', $passwordReset->email)->first();
		$user->password = $request->password;
        
		if($user->save()){
			
			$passwordReset->delete();
			$user->notify(new PasswordResetSuccess($passwordReset));
			return redirect()->home()->with('success','Password updated successfully !!');
		}else{
			return view('password-error')->withErrors(['Network Error, Please Re-try again !!']);
		}

	}
	
	public function contact(Request $request){
        if($request->isMethod('post')){
			$post = $request->except('_token');
			// print_r($post);
			// exit();
			$post['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
			$post['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();
            $insert = DB::table('contacts')->insert($post);
            //exit();
            if($insert){
                //$logo = URL::to('/').'/images/config/'.Config::get_field('logo');
                // Mail::send('emails.contact_to_admin', ['data' => $post], function ($message)
                // {
                //     $message->to(env('ADMIN_EMAIL'), env('EMAIL_TITLE'))->subject('Contact us query recieved');
                //     $message->from(env('ADMIN_EMAIL'), env('EMAIL_TITLE'));
                
                // });
                return redirect('contact-us')->with('success', 'Your query has been submitted to our support. We will contact you soon.');
            }else{
                return redirect('contact-us')->with('error', 'Error in submitting a query. Please try again');
            }
        }
        return view('static.contact-us');
	}
	
	
	
	
}
