<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipSaleCounter extends Model
{
    protected $table = 'vip_sale_counter';
	protected $fillable = ['start_date','end_date','status'];
}
