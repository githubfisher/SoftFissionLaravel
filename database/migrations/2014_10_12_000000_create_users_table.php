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
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0);
            $table->string('name', 64)->default('');
            $table->char('mobile', 11)->unique()->default('');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('email', 32)->unique()->default('');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64)->default('');
            $table->string('openid', 32)->default('');
            $table->string('nickname', 64)->default('');
            $table->string('headimgurl', 255)->default('');
            $table->softDeletes();
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
        Schema::dropIfExists('user');
    }
}
