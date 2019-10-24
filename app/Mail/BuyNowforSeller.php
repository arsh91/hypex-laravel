<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\OrdersModel;


class BuyNowforSeller extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderID)
    {
        $this->id = $orderID;
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
        $fetchData = OrdersModel::with(['product','size','shipping','billing'])->where(['id'=>$id])->first()->toArray();

        return $this->from('info@hypex.com', 'HYPEX')
                    ->subject('HYPEX ORDER SOLD SUCCESSFULLY !!')
                    ->view('email.order-sold')
                    ->with([
                        'name' => $fName.' '.$lName,
                        'totalPrice'=> $fetchData['total_price'],
                        'prodName'=> $fetchData['product']['product_name'],
                        'style'=> $fetchData['product']['style'],
                        'imageLink'=> $fetchData['product']['product_image_link'][0],
                        'size'=> $fetchData['size']['size'],
                        'billingAdd' => $fetchData['billing']['full_address'],
                        'billingCity' => $fetchData['billing']['street_city'],
                        'billingCountry' => $fetchData['billing']['country'],
                        'billingZip' => $fetchData['billing']['zip_code'],
                        'shippingAdd' => $fetchData['shipping']['full_address'],
                        'shippingCity' => $fetchData['shipping']['street_city'],
                        'shippingCountry' => $fetchData['shipping']['country'],
                        'shippingZip' => $fetchData['shipping']['zip_code'],
                    ]);
    }
}
