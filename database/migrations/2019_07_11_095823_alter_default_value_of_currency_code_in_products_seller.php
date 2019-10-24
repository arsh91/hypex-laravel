<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDefaultValueOfCurrencyCodeInProductsSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_seller', function (Blueprint $table) {
	        $table->string('currency_code', 10)->after('sell_expiry_date')->default('CAD')->comment('By Default CAD')->change();
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
