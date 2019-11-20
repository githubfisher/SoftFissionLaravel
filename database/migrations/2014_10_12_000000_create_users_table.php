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
            $table->unsignedInteger('pid')->default(0);
            $table->string('name', 64)->default('');
            $table->char('mobile', 11)->unique()->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('email', 32)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64)->default('');
            $table->string('openid', 32)->unique()->nullable();
            $table->string('nickname', 64)->default('');
            $table->string('headimgurl')->default('');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pid')->default(0);
            $table->string('name', 64)->default('');
            $table->char('mobile', 11)->unique()->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('email', 32)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64)->default('');
            $table->string('openid', 32)->nullable();
            $table->string('nickname', 64)->default('');
            $table->string('headimgurl')->default('');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pid')->default(0);
            $table->string('name', 64)->default('');
            $table->char('mobile', 11)->unique()->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('email', 32)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 64)->default('');
            $table->string('openid', 32)->nullable();
            $table->string('nickname', 64)->default('');
            $table->string('headimgurl')->default('');
            $table->softDeletes();
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
        Schema::dropIfExists('operations');
        Schema::dropIfExists('admins');
    }
}
