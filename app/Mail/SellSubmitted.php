<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductSellerModel;

class SellSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bidderID)
    {
        $this->id = $bidderID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        ini_set('memory_limit','-1');

        $user = Auth::user();
		$fName = $user->first_name;
		$lName = $user->last_name;
        
        $id = $this->id;
        $fetchData = ProductSellerModel::with(['product','size','billingAddress','shippingAddress'])->where(['id'=>$id])->first()->toArray();
        
        return $this->from('info@hypex.com', 'HYPEX')
                    ->subject('HYPEX SELL OFFER SUBMITTED SUCCESSFULLY !!')
                    ->view('email.sell-submitted')
                    ->with([
                        'name' => $fName.' '.$lName,
                        'sellPrice'=> $fetchData['ask_price'],
                        'prodName'=> $fetchData['product']['product_name'],
                        'style'=> $fetchData['product']['style'],
                        'imageLink'=> $fetchData['product']['product_image_link'][0],
                        'size'=> $fetchData['size']['size'],
                        'billingAdd' => $fetchData['billing_address']['full_address'],
                        'billingCity' => $fetchData['billing_address']['street_city'],
                        'billingCountry' => $fetchData['billing_address']['country'],
                        'billingZip' => $fetchData['billing_address']['zip_code'],
                        'shippingAdd' => $fetchData['shipping_address']['full_address'],
                        'shippingCity' => $fetchData['shipping_address']['street_city'],
                        'shippingCountry' => $fetchData['shipping_address']['country'],
                        'shippingZip' => $fetchData['shipping_address']['zip_code'],
                    ]);
    }
}
