<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            /*$table->string('name',100);
            $table->string('email',150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',255);
            $table->rememberToken();
            $table->timestamps();*/
			$table->string('first_name', 100);
			$table->string('last_name', 100);
			$table->string('user_name', 100);
			$table->string('email',150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',255);
			$table->string('phone', 100)->nullable($value = true);
			$table->string('city', 100)->nullable($value = true);
			$table->string('state', 100)->nullable($value = true);
			$table->string('country', 100)->nullable($value = true);
			$table->string('postal_code', 100)->nullable($value = true);
			$table->tinyInteger('is_admin')->default('0');
			$table->tinyInteger('status')->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
