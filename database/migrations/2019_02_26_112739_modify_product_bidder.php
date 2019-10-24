<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProductBidder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_bidder', function (Blueprint $table) {
			
			$table->unsignedInteger('shiping_address_id')->after('bid_price');
			$table->foreign('shiping_address_id')->references('id')->on('users_shipping_address');
			$table->unsignedInteger('billing_address_id')->after('bid_price');
			$table->foreign('billing_address_id')->references('id')->on('users_billing_address');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
