<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('users_billing_address', function (Blueprint $table) {
			
            $table->string('province', 100)->after('country')->nullable();
            $table->string('phone_number', 100)->after('zip_code')->nullable();
            $table->string('last_name', 100)->after('user_id')->nullable();
            $table->string('first_name', 100)->after('user_id')->nullable();
			
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
