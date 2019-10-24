<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('subscription_plans');
            $table->decimal('price', 15, 2)->default('0.00');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('payment_gateway', 100)->nullable();
            $table->tinyInteger('status')->default('0')->comment('0 = Cancelled, 1 = active, 2 = expired');
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
        Schema::dropIfExists('subscriptions');
    }
}
