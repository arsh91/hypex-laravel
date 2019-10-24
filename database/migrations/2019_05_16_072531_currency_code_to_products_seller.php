<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CurrencyCodeToProductsSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_seller', function (Blueprint $table) {
	        $table->string('currency_code', 10)->after('sell_expiry_date')->default('USD')->comment('By Default USD');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_seller', function (Blueprint $table) {
            //
        });
    }
}
