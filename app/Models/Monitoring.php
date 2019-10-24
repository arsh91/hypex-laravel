<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';
	protected $fillable = ['product_id','stockx_bid_url','stockx_ask_url','status'];
}
