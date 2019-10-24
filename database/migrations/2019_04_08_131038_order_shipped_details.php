<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderShippedDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipped_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->string('shipsation_order_id', 100);
            $table->string('shipment_id', 100);
            $table->string('ship_date', 50);
            $table->string('shipment_cost', 50);
            $table->string('tracking_number', 100);
            $table->string('carrier_code', 100);
            $table->string('service_code', 100);
            $table->string('package_code', 100);
            $table->text('label_data');
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
        Schema::dropIfExists('order_shipped_details');
    }
}
