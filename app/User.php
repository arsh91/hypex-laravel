<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Notifications\CustomPasswordReset;
use App\APIModels\UserProfile; 
use App\APIModels\UserSocialProfile; 

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name','user_name', 'email', 'password' , 'created_at', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['full_name'];
	
	public function setPasswordAttribute($pass){
		$this->attributes['password'] = Hash::make($pass);
	}
	
	public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }

    public function getFullNameAttribute(){
        return ucfirst($this->attributes['first_name']).' '.$this->attributes['last_name'];
    }
	public function userProfile()
    {
       return $this->hasOne('App\APIModels\UserProfile','user_id','id');
    }
	public function userSocialProfile()
    {
       return $this->hasOne('App\APIModels\UserSocialProfile','user_id','id');
    }
	public function AauthAcessToken(){
		return $this->hasMany('\App\OauthAccessToken');
    }
     
    public function userbids(){
        return $this->hasMany('App\Models\ProductsBidder','user_id','id');
        }
    public function usersells(){
        return $this->hasMany('App\Models\ProductSellerModel','user_id','id');
    }

    public function subcription(){
        return $this->hasOne('App\Models\SubscriptionsModel','user_id','id')->where(['status' => '1']);
    }

    
}
