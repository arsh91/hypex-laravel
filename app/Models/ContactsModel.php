<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactsModel extends Model
{
    protected $table = 'contacts';
	protected $fillable = ['name','email','subject','reply','message','status','created_at','updated_at'];
    
}
