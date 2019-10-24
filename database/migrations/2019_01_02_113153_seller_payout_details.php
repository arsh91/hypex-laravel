<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SellerPayoutDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_payout_details', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('product_seller_id');
			$table->foreign('product_seller_id')->references('id')->on('products_seller');
			$table->string('stripe_token', 255);
			$table->string('stripe_customer_id', 150);
            $table->text('stripe_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_payout_details');
    }
}
