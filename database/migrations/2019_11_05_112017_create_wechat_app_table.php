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
        Schema::create('we_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('app_id', 20);
            $table->string('nick_name', 32);
            $table->string('head_img');
            $table->string('user_name', 16);
            $table->string('qrcode_url');
            $table->string('refresh_token', 64);
            $table->unsignedTinyInteger('service_type_info')->comment('公众号类型: 0订阅号 1由历史老帐号升级后的订阅号 2服务号 ');
            $table->tinyInteger('verify_type_info')->comment('认证类型: -1未认证，0微信认证 1新浪微博认证 2腾讯微博认证 3已资质认证通过但还未通过名称认证 4已资质认证通过，还未通过名称认证，但通过了新浪微博认证 5已资质认证通过，还未通过名称认证，但通过了腾讯微博认证');
            $table->string('alias')->nullable();
            $table->string('principal_name', 60)->comment('主体');
            $table->string('signature')->comment('签名');
            $table->unsignedTinyInteger('keyword_reply')->comment('关键词回复, 0未开启');
            $table->unsignedTinyInteger('anytype_reply')->comment('任意回复: 0未开启');
            $table->unsignedTinyInteger('subscribe_reply')->comment('关注回复: 0未开启');
            $table->string('funcscope_category')->comment('授权权限集');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unique('app_id', 'app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_apps');
    }
}
