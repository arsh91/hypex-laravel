<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBidderPayout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bidder_payout_details', function (Blueprint $table) {
            
			$table->string('stripe_payment_email', 100)->after('stripe_customer_id');
			$table->string('invoice_prefix', 100)->after('stripe_details');
			$table->renameColumn('stripe_token', 'default_source');
			
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
