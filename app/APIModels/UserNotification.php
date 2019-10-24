<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\UserNotification;
use App\User;


class UserNotification extends Model
{
    protected $table ='users_notification_settings';
	protected $fillable = ['user_id','notification_type','status'];
	
}
