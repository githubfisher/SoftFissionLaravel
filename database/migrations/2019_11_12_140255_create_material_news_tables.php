<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialNewsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('app_id', 20)->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->timestamps();
        });

        Schema::create('material_news_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->string('thumb_media_id', 64)->default('')->comment('图文消息的封面图片素材id（必须是永久mediaID）');
            $table->unsignedTinyInteger('sort');
            $table->unsignedTinyInteger('show_cover_pic')->comment('是否显示封面，0为false，即不显示，1为true，即显示');
            $table->string('title')->default('');
            $table->string('author', 32)->nullable()->default('')->comment('作者');
            $table->string('digest')->nullable()->default('')->comment('图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空');
            $table->string('thumb_url')->default('')->comment('封面图片URL');
            $table->string('url')->default('')->comment('图文页的URL');
            $table->string('content_source_url')->nullable()->default('')->comment('图文消息的原文地址，即点击“阅读原文”后的URL');
            $table->text('content')->comment('图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS');
            $table->unsignedBigInteger('poster_id')->nullable()->default(0)->comment('趣味封面ID');
            $table->unsignedBigInteger('image_id')->nullable()->comment('图片ID');
            $table->timestamps();

            $table->foreign('news_id')
                  ->references('id')->on('material_news')
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
        Schema::dropIfExists('material_news');
        Schema::dropIfExists('material_news_detail');
    }
}
