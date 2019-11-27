<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('we_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->unsignedTinyInteger('type')->default(0)->comment('菜单类型: 1默认 2个性化');
            $table->string('filter')->nullable()->comment('个性化筛选设置');
            $table->unsignedTinyInteger('status')->default(0)->comment('启用状态: 1是 0否');
            $table->timestamps();
        });

        Schema::create('we_menu_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('pid')->default(0);
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->string('name');
            $table->unsignedTinyInteger('status')->default(0)->comment('启用状态: 1是 0否');
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('we_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_menu_details');
        Schema::dropIfExists('we_menus');
    }
}
