<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class PayoutInfoModel extends Model
{
    protected $table = 'user_payout_info';
	protected $fillable = ['user_id','payout_email','status'];
	
}
