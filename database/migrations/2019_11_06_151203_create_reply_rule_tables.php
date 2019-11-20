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
        Schema::create('we_rule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->string('scene', 9)->default('');
            $table->string('title', 128)->default('');
            $table->unsignedTinyInteger('reply_rule')->default(1)->comment('回复规则: 1全部 2随机');
            $table->unsignedTinyInteger('status')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();

            $table->index('app_id');
        });

        Schema::create('we_keyword', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->string('keyword', 64);
            $table->unsignedTinyInteger('match_type')->default(1)->comment('匹配规则: 1全匹配 2半匹配');
            $table->timestamps();

            $table->foreign('rule_id')
                  ->references('id')->on('we_rule')
                  ->onDelete('cascade');

            $table->index(['rule_id', 'keyword']);
        });

        Schema::create('we_reply', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->unsignedTinyInteger('difference')->default(1)->comment('是否区分男女: 1不区分 2区分');
            $table->unsignedTinyInteger('reply_type')->nullable()->default(1)->comment('回复消息类型: 1文本 2图文 3图片 4音频 5视频 6位置 ...');
            $table->unsignedTinyInteger('reply_type_female')->nullable()->default(1);
            $table->text('content')->nullable();
            $table->text('content_female')->nullable();
            $table->unsignedInteger('material_id')->nullable()->default(0)->comment('素材ID');
            $table->unsignedInteger('material_id_female')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('rule_id')
                  ->references('id')->on('we_rule')
                  ->onDelete('cascade');

            $table->index('rule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_keyword');
        Schema::dropIfExists('we_reply');
        Schema::dropIfExists('we_rule');
    }
}
