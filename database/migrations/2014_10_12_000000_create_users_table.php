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
            $table->bigIncrements('id');
            $table->string('google_id'); // For Login With Google
            $table->string('title');
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verify_token');
            $table->string('password');
            $table->string('company_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->integer('country')->nullable();
            $table->integer('status'); // At first 0 , 0= Inactive , 1 = Active
            $table->integer('profile_status')->nullable();
            $table->string('profile_image'); // User Profile Image File Name
            $table->tinyInteger('role_id');
            $table->string('role');
            $table->integer('forgot_password_flag');
            $table->string('forgot_password_token');
            $table->datetime('forgot_password_time');
            $table->datetime('last_login_at');
            $table->string('last_login_ip');
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
