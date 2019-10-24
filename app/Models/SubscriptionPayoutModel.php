<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayoutModel extends Model
{
    protected $table = 'subscription_payout_details';
	protected $fillable = ['subscription_id', 'stripe_plan_id','default_source','stripe_customer_id','stripe_payment_email','stripe_details','invoice_prefix'];
}
