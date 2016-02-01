<?php

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
            $table->string('username', 36);
            $table->string('password', 100)->nullable();
            $table->string('email', 48);
            $table->string('nickname', 12);
            $table->rememberToken();
            $table->nullableTimestamps();

            $table->unique('username');

            $table->index('email');
            $table->index('nickname');
            $table->index('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
