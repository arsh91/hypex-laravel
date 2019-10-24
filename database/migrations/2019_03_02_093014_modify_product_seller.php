<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyProductSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_seller', function (Blueprint $table) {
			
			$table->unsignedInteger('shiping_address_id')->after('ask_price');
			$table->foreign('shiping_address_id')->references('id')->on('users_shipping_address');
			$table->unsignedInteger('billing_address_id')->after('ask_price');
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
