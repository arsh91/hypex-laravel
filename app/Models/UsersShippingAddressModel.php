<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersShippingAddressModel extends Model
{
    protected $table = 'users_shipping_address';
	protected $fillable = ['user_id','full_address','street_city','country','zip_code','province','phone_number','last_name','first_name','default'];

	public function users(){
		return $this->belongsTo('App\User','user_id','id');
    }
}
