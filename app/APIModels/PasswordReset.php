<?php

namespace App\APIModels;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
	protected $fillable = [
        'email', 'token', 'user_id'
    ];
	// public $timestamps = false;
}
