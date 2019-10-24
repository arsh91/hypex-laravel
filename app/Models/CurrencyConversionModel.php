<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversionModel extends Model
{
	protected $table = 'currency_conversion';
	protected $fillable = ['currency_code','conversion_rate','status'];

}
