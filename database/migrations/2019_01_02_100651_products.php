<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
			$table->string('product_name', 100)->unique();
			$table->string('product_nick_name', 255);
			$table->unsignedInteger('category_id');
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
			$table->unsignedInteger('brand_id');
			$table->foreign('brand_id')->references('id')->on('brands')->onDelete('restrict');
			$table->unsignedInteger('brand_type_id');
			$table->foreign('brand_type_id')->references('id')->on('brand_types')->onDelete('restrict');
			$table->unsignedInteger('size_type_id');
			// $table->foreign('size_type_id')->references('id')->on('product_size_types')->onDelete('restrict');
			$table->string('style', 100);
			$table->string('color', 100);
			$table->text('description')->nullable();
			$table->text('product_images');
			$table->text('other_product_images');
			$table->dateTime('release_date');
			$table->unsignedInteger('start_counter');
			$table->tinyInteger('trending')->default('0');
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
        Schema::dropIfExists('products');
    }
}
