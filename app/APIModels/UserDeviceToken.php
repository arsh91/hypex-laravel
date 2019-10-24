<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\UserDeviceToken;
use App\User;


class UserDeviceToken extends Model
{
    protected $table ='users_device_token';
	protected $fillable = ['user_id','device_token','platform','status'];
	
}
