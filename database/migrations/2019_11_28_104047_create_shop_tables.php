<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('type')->comment('店铺类型');
            $table->string('name')->comment('店名');
            $table->unsignedBigInteger('mobile');
            $table->string('introduction')->nullable()->comment('简介');
            $table->string('headimgurl')->nullable()->comment('店铺图标');
            $table->string('telephone')->nullable();
            $table->string('qrcode_url')->nullable()->comment('二维码名片');
            $table->string('wechat')->nullable()->comment('微信号');
            $table->string('weibo')->nullable()->comment('微博号');
            $table->string('douyin')->nullable()->comment('抖音号');
            $table->decimal('location_x', 9, 6);
            $table->decimal('location_y', 9, 6);
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable()->comment('详细地址');
            $table->string('start_at')->comment('开始营业时间');
            $table->string('end_at')->comment('结束营业时间');
            $table->text('details')->nullable()->comment('店铺详情');
            $table->timestamps();

            $table->index('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('经营项目名称');
        });

        Schema::create('shops_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('project_id');

            $table->index('shop_id');
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('品牌/品种名称');
        });

        Schema::create('shops_brands', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('brand_id');

            $table->index('shop_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('shops_projects');
        Schema::dropIfExists('shops_brands');
    }
}
