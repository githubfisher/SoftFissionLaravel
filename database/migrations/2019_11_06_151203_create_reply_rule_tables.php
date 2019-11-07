<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyRuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->default('');
            $table->string('title', 128)->default('');
            $table->unsignedTinyInteger('reply_rule')->default(1)->comment('回复规则: 1全部 2随机');
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('keywords', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->string('keyword', 64);
            $table->unsignedTinyInteger('match_type')->default(1)->comment('匹配规则: 1全匹配 2半匹配');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rule_id')
                  ->references('id')->on('rules')
                  ->onDelete('cascade');
        });

        Schema::create('replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->unsignedTinyInteger('difference')->comment('是否区分男女: 1不区分 2区分');
            $table->unsignedTinyInteger('reply_type')->comment('回复消息类型: 1文本 2图文 3图片 4音频 5视频 6位置 ...');
            $table->unsignedTinyInteger('reply_type_female');
            $table->text('content');
            $table->text('content_female');
            $table->unsignedInteger('material_id')->comment('素材ID');
            $table->unsignedInteger('material_id_female');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rule_id')
                  ->references('id')->on('rules')
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
        Schema::dropIfExists('rules');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('replies');
    }
}
