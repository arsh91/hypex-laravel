<?php

namespace App\Http\Controllers\v1\website;

use Illuminate\Http\Request;
class testController extends Controller
{
    function iosPushFunction(){
			
			$token = '0bb8488d05a7f00fd23c9dfcd6bf4f2769149f98174242ddb712d9cffd79a877';
			
			
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
							  'values'=>'test'
						  )
					  ); 
				
				$payload = json_encode($load);	

				$apnsMessage = "Testing Code";

				$test = fwrite($apns, $apnsMessage, strlen($apnsMessage));  
				if (!$test)
			        $msg_iphone = 'Message not delivered';

			    else
			        $msg_iphone = 'Message successfully delivered';
		
			fclose($apns);
		}
}
