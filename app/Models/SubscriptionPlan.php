<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';
	protected $fillable = ['duration','title','feature_1','feature_2','feature_3','feature_4','price','status'];

}
