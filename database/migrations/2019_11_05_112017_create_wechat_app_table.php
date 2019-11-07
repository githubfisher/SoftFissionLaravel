<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_app', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('app_id', 20)->default('');
            $table->string('nick_name', 32)->default('');
            $table->string('head_img')->default('');
            $table->string('user_name', 16)->default('');
            $table->string('qrcode_url')->default('');
            $table->string('refresh_token', 64)->default('');
            $table->unsignedTinyInteger('service_type_info')->default(0)->comment('公众号类型: 0订阅号 1由历史老帐号升级后的订阅号 2服务号 ');
            $table->tinyInteger('verify_type_info')->default(0)->comment('认证类型: -1未认证，0微信认证 1新浪微博认证 2腾讯微博认证 3已资质认证通过但还未通过名称认证 4已资质认证通过，还未通过名称认证，但通过了新浪微博认证 5已资质认证通过，还未通过名称认证，但通过了腾讯微博认证');
            $table->integer('alias')->default(0);
            $table->string('principal_name', 60)->default('')->comment('主体');
            $table->string('signature')->default('')->comment('签名');
            $table->unsignedTinyInteger('keyword_reply')->default(0)->comment('关键词回复, 0未开启');
            $table->unsignedTinyInteger('anytype_reply')->default(0)->comment('任意回复: 0未开启');
            $table->unsignedTinyInteger('subscribe_reply')->default(0)->comment('关注回复: 0未开启');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')->on('user')
                  ->onDelete('cascade');

            $table->unique('app_id', 'app_id');
            $table->index('user_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wechat_app');
    }
}
