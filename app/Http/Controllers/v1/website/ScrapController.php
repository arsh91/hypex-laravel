<?php

namespace App\Http\Controllers\v1\website;
use App\Http\Controllers\Controller;
use App\Models\ProductsModel;
use App\Models\ProductsBidder;
use App\Models\ProductSizeTypes;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Sunra\PhpSimple\HtmlDomParser;
use DOMDocument;

use Illuminate\Http\Request;

class ScrapController extends Controller
{
    
    public function stockxScrap(){
        
        $productID = '17';
        $stockxURL = "http://stockx.com/air-jordan-1-retro-high-off-white-university-blue";
        
        $user_id = Auth::id();
        
        $productData  = ProductsModel::with(['productSizeTypes','productSizes',])
                       ->findOrFail($productID)
                       ->toArray();
        
        if(!empty($productData) && is_array($productData)){
            $retailPrice = $productData['retail_price'];
        }
        // View Bids       - State 300
        // View Sell/Asks  - State 400
        $homepage = file_get_contents($stockxURL); 
        //echo $homepage;
        
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($homepage);
        libxml_clear_errors();

        if($dom->loadHTML($homepage))
        {
            $scripts = $dom->getElementsByTagName('script');
            //echo"<pre>"; print_r($scripts); die;
            for ($i = 0; $i < $scripts->length; $i++)
            {
                if($i == '29'){ // 29th script on the DOM page
                   $script = $scripts->item($i);
                   //echo"<pre>"; print_r($script); die;
                   $scriptCode = $script->nodeValue;
                   $fetchSKU = explode(":",$scriptCode);
                   
                   
                   $positionArray = $fetchSKU['10']; // 10th tag for fetching SKU
                   $fetchSKU = explode(",",$positionArray);
                   $sku = current($fetchSKU);
                   $sku = trim(str_replace('"', '', $sku));
                }
            }
            //echo $sku; echo "<br/>"; die;
            if($sku != ''){
                
                $url = "https://stockx.com/api/products/$sku/activity?currency=USD&state=400&limit=10&page=1&sort=amount&order=ASC";
                
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => 'SCRAP DATA'
                ]);

                $resp = curl_exec($curl);
                curl_close($curl);
                $fetchSKU = explode("},{",$resp);
                //var_dump($fetchSKU);
                //echo"<pre>"; print_r($fetchSKU); die;
                
                foreach($fetchSKU as $key=>$value){

                    $bidsData = explode(",",$value);
                    
                    $sizeData = $bidsData[count($bidsData)-8];
                    $sizeQuotes = str_replace('"shoeSize":',"",$sizeData);
                    $size = str_replace('"', '', $sizeQuotes);
                    
                    $amountData = $bidsData[count($bidsData)-2];
                    $amount = str_replace('"localAmount":',"",$amountData);

                    if(!is_numeric($amount)){
                        continue;
                    }

                    $currencyData = $bidsData[count($bidsData)-1];
                    $currencyQuotes = str_replace('"localCurrency":',"",$currencyData);
                    $currency = str_replace('"', '', $currencyQuotes);
                    
                    if(is_numeric($currency)){
                        continue;
                    }
                    

                    $sizeID = $this->getProductSizeID($size,$productID);
                    //echo $sizeID; die;
                    $expiryDate = Carbon::now()->addDays(15); // Valid for Default 15 days
                    $sizeID = 227;
                    if($sizeID > 0){

                        $bidderPrimaryID = ProductsBidder::firstOrCreate([
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'bid_price' => $amount,
                            'status' => 1
                        ],
                        [
                            'user_id' => $user_id,
                            'product_id' => $productID,
                            'size_id' => $sizeID,
                            'actual_price' => $retailPrice,
                            'bid_price' => $amount,
                            'shipping_price' => 0,
                            'total_price' => $amount,
                            'commission_price' => 0,
                            'processing_fee' => 0,
                            'billing_address_id' => 80, // Default billing address
                            'shiping_address_id' => 80, // Default shipping address
                            'bid_expiry_date' => $expiryDate, // 15 days 
                            'currency_code' => $currency,
                            'status' => 1
                        ]); 
                        
                        //echo"<pre>"; print_r($bidderPrimaryID); die;
                    }else{
                        continue;
                    }
                    
                    echo "BIDS IMPORTED SUCCESSFULLY !!"; echo "<br/>";
                    
                }
                
            }

            
            
        }
        
    }
    
    
    
    
    public function getProductSizeId($size,$productID){
		
		$productDetails = ProductsModel::with(['productSizeTypes','productSizes'])->findOrFail($productID)->toArray();
		$productAllSizes = $productDetails['product_sizes'];
		foreach($productAllSizes as $key=>$value){
			
			if($value['size'] == $size){
				return $value['id'];
			}else{
                return 0;
            }
			
		}		
		
	}
}
