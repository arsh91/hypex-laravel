<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\UserProfile;
use App\User;


class UserProfile extends Model
{
    protected $table ='user_profile';
	protected $fillable = ['user_id','shoe_size','streetwear_size'];
	
	public function userProfile()
    {
       return $this->BelongsTo('App\Models\User','id','user_id');
    }
}
