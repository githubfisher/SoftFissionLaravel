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
        Schema::create('we_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->timestamps();

            $table->index(['user_id', 'app_id', 'media_id']);
        });

        Schema::create('we_news_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('news_id');
            $table->string('thumb_media_id', 64)->default('')->comment('图文消息的封面图片素材id（必须是永久mediaID）');
            $table->unsignedTinyInteger('sort');
            $table->unsignedTinyInteger('show_cover_pic')->default(1)->comment('是否显示封面，0为false，即不显示，1为true，即显示');
            $table->string('title')->default('');
            $table->string('author', 32)->nullable()->default('')->comment('作者');
            $table->string('digest')->nullable()->default('')->comment('图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空');
            $table->string('thumb_url')->default('')->comment('封面图片URL');
            $table->string('url')->default('')->comment('图文页的URL');
            $table->string('content_source_url')->nullable()->default('')->comment('图文消息的原文地址，即点击“阅读原文”后的URL');
            $table->text('content')->nullable()->comment('图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS');
            $table->unsignedBigInteger('poster_id')->nullable()->default(0)->comment('趣味封面ID');
            $table->unsignedBigInteger('image_id')->nullable()->comment('图片ID');
            $table->timestamps();

            $table->foreign('news_id')
                  ->references('id')->on('we_news')
                  ->onDelete('cascade');

            $table->index(['news_id', 'thumb_media_id']);
        });

        Schema::create('we_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->nullable()->default('');
            $table->string('name', 64)->nullable()->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->string('url')->nullable()->default('')->comment('微信URL');
            $table->dateTime('expire_at')->nullable()->comment('有效截止日期');
            $table->string('origin_url')->nullable()->default('')->comment('云存储URL');
            $table->timestamps();

            $table->index(['user_id', 'app_id', 'media_id']);
        });

        Schema::create('we_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->nullable()->default('');
            $table->string('name', 64)->nullable()->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->string('url')->nullable()->default('')->comment('微信URL');
            $table->dateTime('expire_at')->nullable()->comment('有效截止日期');
            $table->string('origin_url')->nullable()->default('')->comment('云存储URL');
            $table->text('description')->nullable()->comment('描述');
            $table->timestamps();

            $table->index(['user_id', 'app_id', 'media_id']);
        });

        Schema::create('we_voice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->nullable()->default('');
            $table->string('name', 64)->nullable()->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->string('url')->nullable()->default('')->comment('微信URL');
            $table->dateTime('expire_at')->nullable()->comment('有效截止日期');
            $table->string('origin_url')->nullable()->default('')->comment('云存储URL');
            $table->timestamps();

            $table->index(['user_id', 'app_id', 'media_id']);
        });

        Schema::create('we_thumb', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->nullable()->default('');
            $table->string('name', 64)->nullable()->default('');
            $table->string('media_id', 64)->nullable()->default('');
            $table->string('url')->nullable()->default('')->comment('微信URL');
            $table->dateTime('expire_at')->nullable()->comment('有效截止日期');
            $table->string('origin_url')->nullable()->default('')->comment('云存储URL');
            $table->timestamps();

            $table->index(['user_id', 'app_id', 'media_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_news_detail');
        Schema::dropIfExists('we_news');
        Schema::dropIfExists('we_image');
        Schema::dropIfExists('we_video');
        Schema::dropIfExists('we_voice');
        Schema::dropIfExists('we_thumb');
    }
}
