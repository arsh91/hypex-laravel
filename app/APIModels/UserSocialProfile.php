<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;
use App\APIModels\UserSocialProfile;


class UserSocialProfile extends Model
{
    protected $table ='user_social_profile';
	protected $fillable = ['user_id','social_id','social_type'];
	public function User()
    {
       return $this->BelongsTo('App\User','user_id','id');
    }
	
}
