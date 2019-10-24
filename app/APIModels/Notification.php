<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\Notification;
use App\User;


class Notification extends Model
{
    protected $table ='notifications';
	protected $fillable = ['user_id','notification_type','notification','product_id','status'];
	
}
