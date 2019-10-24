<?php

	namespace App\Traits;
	use Image;

	trait CustomFunction {
 
	  	public function downloadImage($image, $width = 300, $height = 200){
	  		try{
	  			// echo '<pre>','<br>';
	  			// print_r($image); 
	  			if(!empty($image)){
	  				// print_r($image);
	  				// $arrContextOptions=array(
					    // "ssl"=>array(
					        // "verify_peer"=>false,
					        // "verify_peer_name"=>false,
					    // ),
					// );
		  			// $content = file_get_contents($image, false, stream_context_create($arrContextOptions));
		  			// $img = Image::make($content);
		  			// $img_mime = explode('/',$img->mime());
		  			// $img_path = 'v1/website/products/'.date("Ymdhis").str_random(10).'.'.$img_mime[1];
		  			// $img_path_public = public_path($img_path);
		  			// $img->save($img_path_public);
		  			// $img_path = 'public/'.$img_path;
					// print_r($img_path); exit; 
		  			// print_r($img_path);
					
					//  new function for upload image
					$mime = explode('.',$image); 
					$mime = $mime[count($mime) - 1];
					$temp_name = date("Ymdhis").str_random(10).'.'.$mime;

					$img_path = public_path('v1/website/products/'.$temp_name);
					$this->grab_image($image,$img_path);
					$img_path = 'public/v1/website/products/'.$temp_name;
					/* echo $img_path;
					exit; */
	   				return $img_path;

	  			}else{
	  				// echo '<br>';
	  				// print_r('error'); 
	  				return '';
	  				// exit;
	  			}	
	  		// } catch(\Intervention\Image\Exception\NotReadableException $ex){
	  			// echo '<br>';
  				// print_r($ex->getMessage()); 
				 // exit;
	  			return '';
	  			// return redirect('admin/new-product-import')->withError($ex->getMessage());
	  		}catch(\ErrorException $ex){
	  			// echo '<br>';
  				/* print_r($ex->getMessage());
				 exit; */
	  			return '';
	  			// return redirect('admin/new-product-import')->withError($ex->getMessage());
	  		}
		}
		
		function grab_image($url,$saveto){
			$ch = curl_init ($url);
			curl_setopt($ch, CURLOPT_HEADER, "Content-Type:multipart/form-data");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_USERAGENT,'Hypex');
			curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
			$raw=curl_exec($ch);

			curl_close ($ch);
			if(file_exists($saveto)){
				unlink($saveto);
			}
			
			$fp = fopen($saveto,'wb');
			fwrite($fp, $raw);
			fclose($fp);
		}
		
		function uploadProductImage($image){
			if($image->isValid()){
				$img = Image::make($image);
	  			$img_mime = $image->extension();
	  			$img_path = 'v1/website/products/'.date("Ymdhis").str_random(10).'.'.$img_mime;
	  			$img_path_public = public_path($img_path);
	  			$img->save($img_path_public);
	  			$img_path = 'public/'.$img_path;
	  			return $img_path;
			}else{
				return false;
			}		
		}

		function uploadCategoryImage($image){
			if($image->isValid()){
				$img = Image::make($image);
				$img_mime = $image->extension();
				$img_path = 'v1/website/categories/'.date("Ymdhis").str_random(10).'.'.$img_mime;
				$img_path_public = public_path($img_path);
				$img->save($img_path_public);
				$img_path = 'public/'.$img_path;
				return $img_path;
			}else{
				return false;
			}
		}
	 
	}

?>

