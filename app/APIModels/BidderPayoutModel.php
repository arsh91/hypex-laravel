<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class BidderPayoutModel extends Model
{
    protected $table = 'bidder_payout_details';
	protected $fillable = ['product_bidder_id','default_source','stripe_customer_id','stripe_payment_email','stripe_details','paypal_id','non_refundable_amount','invoice_prefix'];
	
}
