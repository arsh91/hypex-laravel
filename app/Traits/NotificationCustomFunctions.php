<?php

	namespace App\Traits;

	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Auth;

	use App\APIModels\Notification;
	use App\APIModels\ProductsBidder;
	use App\Models\ProductsSeller;
	use App\APIModels\ProductsModel;
	use App\APIModels\UserDeviceToken;

	use App\Helpers\WebHelper;


	trait NotificationCustomFunctions { 
 
	  	function commonFunctionToPush($case,$product_id,$price, $size_id, $user_id){ //dd($price);
	  		// New lowest Selling Offer
	  		if($case == '0'){ 
			 	$users = ProductsBidder::select('user_id')->where([['size_id','=',$size_id],['product_id','=',$product_id],['status','=',1],['user_id','!=',$user_id]])
			 	->orWhere([['size_id','=',$size_id],['product_id','=',$product_id],['actual_price','=',0],['status','=',1],['user_id','!=',$user_id]])->groupBy('user_id')->get()->toArray(); //dd($users);
			 	$product_name = $this->getProductName($product_id);
			 	$message = 'There is a new sell offer on Product '.$product_name;
			 	if(!empty($users)){
			 		foreach($users as $user){
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			}
			// New Highest Buying Offer
			if($case == '1'){
			 	$users = ProductsBidder::select('user_id')->where([['size_id','=',$size_id],['product_id','=',$product_id],['bid_price','<',$price],['status','=',1],['user_id','!=',$user_id]])->groupBy('user_id')->get()->toArray();
			 	$product_name = $this->getProductName($product_id); dd($product_id);
			 	$message = 'There is a new buy offer on Product '.$product_name;
			 	if(!empty($users)){
			 		foreach($users as $user){
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			}
			
			// Buying offer Expiring
			if($case == '2'){ 
				//$this->commonFunctionToPush('2','','', ''); must be added to cron
				$stop_date = date('Y-m-d', strtotime(DATE("Y-m-d") . ' +1 day'));

				$users = ProductsBidder::select('product_id','user_id')->where([['bid_expiry_date','like', '%'.$stop_date.'%'],['status','=',1]])->groupBy('user_id','product_id')->get()->toArray();
				//print_r($users); die;

			 	if(!empty($users)){
			 		foreach($users as $user){
			 			echo $user['user_id'];
			 			$product_name = $this->getProductName($user['product_id']);
			 			$message = "Your bid on Product ".$product_name." expiring tommorrow!"; 
						$this->addNotification($user['user_id'],$case,$message,$user['product_id']);
			 		}
		 		}
			 	
			}

			if($case == '3'){
				//$this->commonFunctionToPush('3','','', ''); must be added to cron
				$stop_date = date('Y-m-d');

				$users = ProductsBidder::select('product_id','user_id')->where([['bid_expiry_date','like', '%'.$stop_date.'%'],['status','=',1]])->groupBy('user_id','product_id')->get()->toArray();
				//print_r($users); die;

			 	if(!empty($users)){
			 		foreach($users as $user){
			 			$product_name = $this->getProductName($user['product_id']);
			 			$message = "Your bid on Product ".$product_name." expiring tommorrow!";
						$this->addNotification($user['user_id'],$case,$message,$user['product_id']);
			 		}
		 		}
			 	
			}

			if($case == '4'){
				//nearby
				//$this->commonFunctionToPush('4',$productID,$bid_price, $size_id); sellOffer
				$size = WebHelper::getSize($size_id,$product_id);
				$minSize = (float)$size - (float)0.5;
				$maxSize = (float)$size + (float)0.5;
				$users = ProductsBidder::whereHas('size' , function($q) use($product_id,$minSize,$maxSize){
							$q->where([['product_id', '=', $product_id],['size', '=', $minSize]])
							->orWhere([['product_id', '=', $product_id],['size','=', $maxSize]]);
						})->select('size_id','user_id')->where([['product_id','=',$product_id],['actual_price','>=',$price],['status','=',1]])->groupBy('user_id','size_id')->get()->toArray();
				//print_r($users); die;

			 	if(!empty($users)){
			 		foreach($users as $user){
			 			$product_name = $this->getProductName($product_id);
			 			$message = "There is a nearby bid on Product ".$product_name;
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			 	
			}

			if($case == '5'){
				//$this->commonFunctionToPush('5',$productID,$ask_price, $size_id); sellOffer
			 	$users = ProductsBidder::select('user_id')->where([['size_id','=',$size_id],['product_id','=',$product_id],['actual_price','>',$price],['status','=',0]])->groupBy('user_id')->get()->toArray();
			 	$product_name = $this->getProductName($product_id);
			 	$message = 'There is a new sell offer on Product '.$product_name.' you had expired bid';
			 	if(!empty($users)){
			 		foreach($users as $user){
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			}

			if($case == '6'){
				//bidOffer (Highest bid on your sell product)
			 	$users = ProductsSeller::select('user_id')->where([['size_id','=',$size_id],['product_id','=',$product_id],['actual_price','<',$price],['status','=',1],['user_id','!=',$user_id]])
			 	->orWhere([['size_id','=',$size_id],['product_id','=',$product_id],['actual_price','=',0],['status','=',1],['user_id','!=',$user_id]])->groupBy('user_id')->get()->toArray();
			 	$product_name = $this->getProductName($product_id);
			 	$message = 'There is a new bid offer on Product '.$product_name;
			 	if(!empty($users)){
			 		foreach($users as $user){
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			}
			
			if($case == '7'){
				//sellOffer (lowest sell on a product you have placed a sell on)
			 	$users = ProductsSeller::select('user_id')->where([['size_id','=',$size_id],['product_id','=',$product_id],['ask_price','>',$price],['status','=',1],['user_id','!=',$user_id]])->groupBy('user_id')->get()->toArray();
			 	$product_name = $this->getProductName($product_id);
			 	$message = 'There is a new sell offer on Product '.$product_name;
			 	if(!empty($users)){
			 		foreach($users as $user){
						$this->addNotification($user['user_id'],$case,$message,$product_id);
			 		}
		 		}
			}
			
			if($case == '8'){
				//$this->commonFunctionToPush('8','','', ''); must be added to cron
				$stop_date = date('Y-m-d', strtotime(DATE("Y-m-d") . ' +1 day'));

				$users = ProductsSeller::select('product_id','user_id')->where([['sell_expiry_date','like', '%'.$stop_date.'%'],['status','=',1]])->groupBy('user_id','product_id')->get()->toArray();
				//print_r($users); die;

			 	if(!empty($users)){
			 		foreach($users as $user){
			 			echo $user['user_id'];
			 			$product_name = $this->getProductName($user['product_id']);
			 			$message = "Your sell offer on Product ".$product_name." expiring tommorrow!";
						$this->addNotification($user['user_id'],$case,$message,$user['product_id']);
			 		}
		 		}
			 	
			}

			if($case == '9'){
				//$this->commonFunctionToPush('9','','', ''); must be added to cron
				$stop_date = date('Y-m-d');

				$users = ProductsSeller::select('product_id','user_id')->where([['sell_expiry_date','like', '%'.$stop_date.'%'],['status','=',1]])->groupBy('user_id','product_id')->get()->toArray();
				//print_r($users); die;

			 	if(!empty($users)){
			 		foreach($users as $user){
			 			$product_name = $this->getProductName($user['product_id']);
			 			$message = "Your sell offer on Product ".$product_name." expiring tommorrow!";
						$this->addNotification($user['user_id'],$case,$message,$user['product_id']);
			 		}
		 		}
			 	
			}

			
		}
		

		function addNotification($userID,$type,$message,$productID){
			//echo $productID; die;
			$saveDetails = Notification::create([
				'user_id' => $userID,
				'notification_type' => $type,
				'notification' => $message,
				'product_id'=>$productID
			]); 
			if($saveDetails->id){
				$devices = UserDeviceToken::select('device_token','platform')->where('user_id',$userID)->get()->toArray(); 
				if(!empty($devices)){
					foreach($devices as $device){
						$token = $device['device_token'];
						if($device['platform'] == 'ios'){ 
							$this->iosPushFunction($token,$message,$userID,$productID);
						}else{
							$this->androidPushFunction($token,$message,$userID,$productID);
						}
					}
				}

			}
		}

		function getProductName($productID){
			//echo $productID; die;
			$productDetails = ProductsModel::select('product_name')->findOrFail($productID)->first(); 
			return $productDetails['product_name'];
		} 


		/****** IOS Push Notification Function ******/
		function iosPushFunction($token,$message,$userId,$productId){
			
			$token = '0bb8488d05a7f00fd23c9dfcd6bf4f2769149f98174242ddb712d9cffd79a877';
			$userData = array('user_id'=>$userId,'product_id'=>$productId);
			
			$streamContext = stream_context_create();
			$url = url('/').'/public/DevPushCertificatesNew.pem'; 
			
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $url);
			stream_context_set_option($streamContext, 'ssl', 'passphrase', 'TrantorHypex');
			stream_context_set_option( $streamContext , 'ssl', 'verify_peer', false); 
			
		$apns = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT ,$streamContext);

					if (!$apns) {
						echo "Failed to connect $err $errstr\n";
					} else {
						echo "Connection OK";
					}			
				
				$load = array(
						  'aps' => array(
							  'alert' => $message,
							  'sound' => 'default',
							  'values'=>$userData
						  )
					  ); 
				
				$payload = json_encode($load);
		
				$apnsMessage = chr(0) . chr(0) . chr(32);
				$apnsMessage .= pack('H*', str_replace(' ', '', $token));
				$apnsMessage .= chr(0) . chr(strlen($payload)) . $payload;

				/*$apnsMessage = pack( 'n', 32 );
				$apnsMessage .= pack('H*', str_replace(' ', '', $token));
				$apnsMessage .= pack( 'n', strlen($payload)) . $payload;*/

				/*$apnsMessage = pack( 'n', 32 ) . pack('H*', $token) . pack( 'n', strlen($payload)) . $payload;
*/  

				$test = fwrite($apns, $apnsMessage, strlen($apnsMessage));  
				if (!$test)
			        $msg_iphone = 'Message not delivered';

			    else
			        $msg_iphone = 'Message successfully delivered';
		
			fclose($apns);
		}
		/****** Android Push Notification Function ***/
		function androidPushFunction($token,$message,$userId,$productId){

				$title = 'Hypex';

				$content = array("message"=>$message,'Product_id'=>$productId,'user_id'=>$userId);

				$APIKey = 'AIzaSyDlPiKQJS10msG0MSTNwqvss515Fys4b50';
				$fields = array(
				'to' => $token,
				'notification' => array('title' => $title, 'body' => $content),
				'data' => array('title' => $title, 'body' => $content));

				$headers = array
				(
				"Authorization: key=$APIKey",
				"Content-Type: application/json"
				);
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
				$result = curl_exec($ch );
				curl_close( $ch );
				//echo $result; die;
		}

	 
	}

?>

