<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->string('introduction')->comment('简介');
            $table->integer('recommend_price')->comment('建议零售价/划线价');
            $table->integer('price')->comment('售价/优惠价');
            $table->integer('cost')->comment('商品实际价值, 用于到店付计算');
            $table->unsignedTinyInteger('type')->comment('商品类型: 1实物 2虚拟物品或服务');
            $table->unsignedTinyInteger('verificate_type')->comment('核销方式: 1单次全额 2多次均额 3多次');
            $table->unsignedTinyInteger('delivery_type')->comment('收货方式: 1到店自取 2快递');
            $table->unsignedTinyInteger('pay_type')->comment('支付方式: 1一次性支付 2到店付');
            $table->unsignedTinyInteger('status')->comment('状态: 0下架 1上架');
            $table->integer('stock')->comment('库存');
            $table->integer('sold')->comment('累计售出');
            $table->dateTime('expire_start')->nullable()->comment('商品/服务兑换有效期');
            $table->dateTime('expire_end')->nullable()->comment('商品/服务兑换有效期');
            $table->text('details')->comment('商品详情');
            $table->timestamps();

            $table->index('shop_id');

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        Schema::create('goods_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('banner_id');
            $table->string('banner_type');
            $table->unsignedTinyInteger('sort');
            $table->timestamps();

            $table->index(['shop_id', 'banner_id', 'banner_type']);
        });

        Schema::create('goods_promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('promotion_id');

            $table->primary(['shop_id', 'promotion_id']);
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedTinyInteger('group_id')->comment('促销类型分组');
            $table->unsignedTinyInteger('sort')->comment('排序 0起, 序号越大优先级越大');
            $table->unsignedTinyInteger('min_count')->comment('最小数量');
            $table->unsignedTinyInteger('min_sum')->comment('最小金额');
            $table->unsignedTinyInteger('sub_sum')->comment('优惠金额');
            $table->unsignedTinyInteger('plus_point')->comment('赠送积分');
            $table->unsignedTinyInteger('limit')->comment('数量限制');
            $table->unsignedTinyInteger('expires')->comment('频率, 每一段时间, 默认分钟');
            $table->string('members')->comment('可享受优惠的会员类型');
            $table->string('products')->comment('适用的商品或分类');
            $table->dateTime('expire_start')->comment('有效期');
            $table->dateTime('expire_end');
            $table->string('introduction')->comment('介绍');
            $table->unsignedTinyInteger('exclusive')->comment('排他性 0否 1是');
            $table->timestamps();

            $table->index(['shop_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('goods_promotions');
        Schema::dropIfExists('goods_banners');
        Schema::dropIfExists('goods');
    }
}
