<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class SellerPayoutModel extends Model
{
    protected $table = 'seller_payout_details';
	protected $fillable = ['product_seller_id','default_source','stripe_customer_id','stripe_payment_email','stripe_details','paypal_id','non_refundable_amount','invoice_prefix'];
	
}
