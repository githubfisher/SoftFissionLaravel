<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('rule_id')->default(0);
            $table->unsignedTinyInteger('difference')->comment('是否区分男女: 1不区分 2区分');
            $table->unsignedTinyInteger('reply_type')->comment('回复消息类型: 1文本 2图文 3图片 4音频 5视频 6位置 ...');
            $table->unsignedTinyInteger('reply_type_female');
            $table->text('content');
            $table->text('content_female');
            $table->unsignedInteger('material_id')->comment('素材ID');
            $table->unsignedInteger('material_id_female');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
