<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FinalUpdatingProductsTableAsPerRequirement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('products', function (Blueprint $table) {

			DB::statement('ALTER TABLE `products` CHANGE `retail_price` `retail_price` VARCHAR(50)  NULL AFTER `color`');
			DB::statement('ALTER TABLE `products` CHANGE `size_type_id` `size_type_id` INT(10) UNSIGNED NOT NULL AFTER `style`');
			DB::statement('ALTER TABLE `products` ADD `season` VARCHAR(25) NULL DEFAULT NULL AFTER `color`');
			
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
