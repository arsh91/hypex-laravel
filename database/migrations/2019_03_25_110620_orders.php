<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('orders');
        
        Schema::create('orders', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('order_ref_number', 100);
			$table->unsignedInteger('product_id');
			$table->foreign('product_id')->references('id')->on('products');
			$table->unsignedInteger('product_size_id');
			$table->foreign('product_size_id')->references('id')->on('product_sizes');
			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->unsignedInteger('seller_id')->nullable();
			$table->foreign('seller_id')->references('id')->on('products_seller');
            $table->unsignedInteger('bidder_id')->nullable();
			$table->foreign('bidder_id')->references('id')->on('products_bidder');
            $table->string('price', 50);
			$table->string('total_price', 50);
            $table->string('shipping_price', 50);
            $table->text('payment_data');
            $table->unsignedInteger('shiping_address_id');
			$table->foreign('shiping_address_id')->references('id')->on('users_shipping_address');
			$table->unsignedInteger('billing_address_id');
			$table->foreign('billing_address_id')->references('id')->on('users_billing_address');
			$table->tinyInteger('status')->default('0');
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
        //
    }
}
