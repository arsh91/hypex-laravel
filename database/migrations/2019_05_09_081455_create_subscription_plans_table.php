<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('duration', 255);
            $table->string('title', 255);
            $table->string('feature_1', 255)->nullable();
            $table->string('feature_2', 255)->nullable();
            $table->string('feature_3', 255)->nullable();
            $table->string('feature_4', 255)->nullable();
            $table->decimal('price', 15, 2)->default('0.00');
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
        Schema::dropIfExists('subscription_plans');
    }
}
