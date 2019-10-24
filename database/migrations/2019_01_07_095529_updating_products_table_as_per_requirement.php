<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatingProductsTableAsPerRequirement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('products', function (Blueprint $table) {

			$table->string('product_nick_name', 255)->nullable()->change();
			//$table->unsignedInteger('size_type_id');
			//$table->foreign('size_type_id')->references('id')->on('product_size_types')->onDelete('restrict');
			$table->string('style', 100)->nullable()->change();
			$table->string('color', 100)->nullable()->change();
			$table->string('retail_price', 100)->nullable(); // as per client we need to keep it as text field
			$table->text('other_product_images')->nullable()->change();
			$table->dateTime('release_date')->nullable()->change();

			//DB::statement('ALTER TABLE `products` CHANGE `retail_price` `retail_price` INT(10) UNSIGNED NOT NULL AFTER `color`');
			//DB::statement('ALTER TABLE `products` CHANGE `size_type_id` `size_type_id` INT(10) UNSIGNED NOT NULL AFTER `style`');
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
