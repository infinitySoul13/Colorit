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
            $table->string('name');
            $table->string('email')->unique()->nullable();

             $table->string('telegram_chat_id')->unique()->nullable();

             $table->string('fio_from_telegram')->default('')->nullable();
            $table->string('phone')->unique()->nullable();

            // $table->string('birthday')->nullable();

             $table->boolean('is_admin')->default(false);

            // $table->timestamp('email_verified_at')->nullable();
             $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
