<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialNewsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('app_id', 20)->default('');
            $table->string('media_id', 64)->default('');
            $table->timestamps();
        });

        Schema::create('news_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->string('thumb_media_id', 64)->default('')->comment('图文消息的封面图片素材id（必须是永久mediaID）');
            $table->string('title', 64)->default('');
            $table->unsignedTinyInteger('sort');
            $table->unsignedTinyInteger('show_cover_pic')->comment('是否显示封面，0为false，即不显示，1为true，即显示');
            $table->string('author', 32)->default('')->comment('作者');
            $table->string('digest')->default('')->comment('图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空');
            $table->string('thumb_url')->default('')->comment('封面图片URL');
            $table->string('url')->default('')->comment('图文页的URL');
            $table->string('content_source_url')->default('')->comment('图文消息的原文地址，即点击“阅读原文”后的URL');
            $table->text('content')->comment('图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS');
            $table->timestamps();

            $table->foreign('news_id')
                  ->references('id')->on('news')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_detail');
    }
}
