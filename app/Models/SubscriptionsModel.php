<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionsModel extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = ['user_id','plan_id','price','start_date','end_date','payment_gateway','status'];
    
    public function plan(){
		  return $this->belongsTo('App\Models\SubscriptionPlan','plan_id','id');
    }

    public function user(){
      return $this->belongsTo('App\User','user_id','id');
    }

}
