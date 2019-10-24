<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingTotalAmountToProductsBidder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_bidder', function (Blueprint $table) {
            $table->string('shipping_price', 50)->after('actual_price');
            $table->string('processing_fee', 50)->after('bid_price');
            $table->string('commission_price', 50)->after('bid_price');
            $table->string('total_price', 50)->after('bid_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_bidder', function (Blueprint $table) {
            //
        });
    }
}
