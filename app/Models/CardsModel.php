<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardsModel extends Model
{
    protected $table = 'user_cards';
	protected $fillable = ['user_id','default_source','stripe_customer_id','stripe_payment_email','stripe_details','paypal_id','invoice_prefix','default','status'];
	
}
