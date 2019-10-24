<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripePlanIdToSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_payout_details', function (Blueprint $table) {
	        $table->string('stripe_plan_id', 250)->after('subscription_id')->default('0')->comment('By Default 0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_payout_details', function (Blueprint $table) {
            //
        });
    }
}
