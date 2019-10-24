<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\APIModels\UserNotification;
use App\APIModels\Notification;
use App\APIModels\UserDeviceToken;

use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
 
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon; 

class UserNotificationsController extends Controller
{
	
	/** 
     * Get Notification Settings API
	*/
	public function getNotificationSettings(Request $request){
		$userID = Auth::id();
		$finalData = array();
		$getData = UserNotification::where('user_id',$userID)->get()->toArray();
		if(!empty($getData)){
			foreach($getData as $data){
				if($data['notification_type'] == "0") { $data['notification_title'] = 'New lowest Selling Offer'; $data['text'] = 'Sent when a new lowest selling offer is placed on an item you have an active Bid on.'; } 	
				if($data['notification_type'] == "1") { $data['notification_title'] = 'New Highest Buying Offer'; $data['text'] = 'Sent when a new highest buying offer is placed on an item you have an active Bid on.';} 	
				if($data['notification_type'] == "2") { $data['notification_title'] = 'Buying offer Expiring'; $data['text'] = 'Sent 24 hours before your Bid expires.';} 	
				if($data['notification_type'] == "3") { $data['notification_title'] = 'Buying offer Expired'; $data['text'] = 'Sent when your Bid has expired.';} 	
				if($data['notification_type'] == "4") { $data['notification_title'] = 'Buyer Nearbuy Match'; $data['text'] = 'Sent if a seller lists an offer at the same price, or lower, within 1/2 size of your existing Bid.';} 	
				if($data['notification_type'] == "5") { $data['notification_title'] = 'Ask Matches Expired Bid'; $data['text'] = 'Sent if a seller lists a new offer that matches one of your expired Bids.';} 	
				if($data['notification_type'] == "6") { $data['notification_title'] = 'New Lowest Selling Offer'; $data['text'] = 'Sent when a new lowest selling offer is placed on an item you have an active Bid on.'; } 	
				if($data['notification_type'] == "7") { $data['notification_title'] = 'Bidding, New Highest Buying Offer'; $data['text'] = 'Sent when a new highest buying offer is placed on an item you have an active Bid on.';} 	
				if($data['notification_type'] == "8") { $data['notification_title'] = 'Selling offer Expiring'; $data['text'] = 'Sent 24 hours before your active Selling offer expires.';} 	
				if($data['notification_type'] == "9") { $data['notification_title'] = 'Selling offer Expired'; $data['text'] = 'Sent when your Selling offer has expired.';} 
				$finalData[] = $data;
			}
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $finalData]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No notification setting exists for this user.','data' => $finalData]); 
		}
		
		
	}
	/** 
     * Set Notification API
     * @return Set Notification 
	*/
	public function SetNotificationSettings(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		
		$notifications = $request['notifications'];
		if(isset($notifications) && !empty($notifications)){
			foreach($notifications as $notification){
				$type = $notification['notification_type'];
				$on = $notification['on'];
				
				$getData = UserNotification::where([['user_id','=',$userID],['notification_type','=',$type]])->get()->first();
				if(!empty($getData)){
					UserNotification::where([['user_id','=',$userID],['notification_type','=',$type]])->update(array('status' => $on));
					$id= $getData->id;
				}else{
					$saveDetails = UserNotification::create([
							'user_id' => $userID,
							'notification_type' => $type,
							'status' => $on
						]);
					$id= $saveDetails->id;
				}
		}
		}
		return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Setting saved successfully.','data'=>(object)[]]);
		
		
		
	}
	
	/** 
     * Get Notification API
	*/
	public function getNotifications(Request $request){
		$userID = Auth::id();
		$offset = $request['offset'];
		$limit = $request['limit'];
		$result = array();
		$getDataCount = Notification::where('user_id',$userID)->get()->toArray();
		$result['totalCount'] = $totalCount = count($getDataCount);
		$result['data'] = Notification::select('id','notification_type','notification', 'product_id','status','created_at')->where('user_id',$userID)->offset($offset)->limit($limit)->get()->toArray();
		if($totalCount>0){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $result]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No notification exist.','data' => $result]); 
		}
		
		
	}
	
	/** 
     * Mark ReadNoitifications API
	*/
	public function markReadNoitifications(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		$nid = $request['notification_id'];
		
		$getData = Notification::where([['user_id','=',$userID],['id','=',$nid]])->get()->first();
		if(!empty($getData)){
			if(Notification::where([['user_id','=',$userID],['id','=',$nid]])->update(array('status' => 1))){
			
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Setting saved successfully.','data'=>(object)[]]);
			}else{
				return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some error occured.','data'=>(object)[]]);
			}
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No notification exist.','data'=>(object)[]]);
		}
		
		
	}
	
	/** 
     * getunreadNotificationCount API
	*/
	public function getunreadNotificationCount(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		
		$getDataCount = Notification::where([['user_id','=',$userID],['status','=',0]])->get()->toArray();
		$result['totalCount'] = $totalCount = count($getDataCount);
		
		
		if($totalCount>0){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'success','data' => $result]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data','data' => $result]); 
		}
		
		
	}
	
	/** 
     * Delete NotificationCount API
	*/
	public function deleteNotifications(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		
		if(Notification::where('user_id',$userID)->delete()){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Notification deleted successfully','data' => (object)[]]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data','data' => (object)[]]); 
		}
		
		
	}

	/** 
     * Delete single NotificationCount API
	*/

	public function deleteSingleNotifications(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		if(Notification::where(['id' => $request['notification_id'],'user_id'=>$userID])->delete()){
			return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Deleted Successfully','data' => (object)[]]); 
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'No data','data' => (object)[]]); 
		}
		
	}

	
	/** 
     * Set Device Token API
	*/
	public function setDeviceToken(Request $request){
		$userID = Auth::id();
		$request = $request->all();
		$device_token = $request['device_token'];
		$platform = $request['platform'];
		
		if(!empty($device_token) && !empty($userID)){
			$getDevice = UserDeviceToken::where([['user_id','=',$userID],['device_token', '=', $device_token]])->get()->toArray();
			if(count($getDevice)==0){
				$saveDetails = UserDeviceToken::create([
						'user_id' => $userID,
						'device_token' => $device_token,
						'platform' => $platform,
						'status' => 1
					]);
				$id= $saveDetails->id;
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Detail saved successfully.','data'=>(object)[]]);
			}else{
				return response()->json(['code'=>1, 'errorCode'=>200, 'message'=>'Detail saved successfully.','data'=>(object)[]]);
			}
			
		}else{
			return response()->json(['code'=>0, 'errorCode'=>406, 'message'=>'Some error occured.','data'=>(object)[]]);
		}
	}
	
	
	
}
