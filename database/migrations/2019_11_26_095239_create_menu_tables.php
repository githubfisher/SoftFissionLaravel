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
            $table->increments('id');
            $table->string('app_id', 20);
            $table->unsignedTinyInteger('type')->comment('菜单类型: 1默认 2个性化');
            $table->string('filter')->nullable()->comment('个性化筛选设置');
            $table->unsignedTinyInteger('status')->comment('启用状态: 1是 0否');
            $table->timestamps();

            $table->index('app_id');
        });

        Schema::create('we_menu_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('pid');
            $table->unsignedInteger('rule_id');
            $table->string('name');
            $table->unsignedTinyInteger('status')->comment('启用状态: 1是 0否');
            $table->timestamps();

            $table->index('menu_id');

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
