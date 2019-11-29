<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrCodeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('we_qrcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_id', 20);
            $table->unsignedInteger('rule_id');
            $table->string('title');
            $table->unsignedTinyInteger('type')->comment('类型: 1临时 2永久');
            $table->unsignedInteger('target_num')->comment('目标数量');
            $table->unsignedInteger('num')->comment('实际数量');
            $table->unsignedTinyInteger('expire_type')->comment('过期时间计算方式 1时长 2时间点');
            $table->dateTime('expire_at')->nullable()->comment('到期时间点');
            $table->integer('expire_in')->comment('有效时长');
            $table->unsignedTinyInteger('status')->comment('状态: 0生成中 1完成');
            $table->timestamps();

            $table->index(['app_id', 'rule_id']);
        });

        Schema::create('we_qrcode_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qrcode_id')->comment('二维码ID');
            $table->string('batch', 12)->comment('批次号: ymdHi');
            $table->string('scene_str')->comment('二维码信息');
            $table->unsignedInteger('scan_num')->comment('扫码数量统计');
            $table->unsignedInteger('subscribe_num')->comment('扫码关注数量统计');
            $table->string('url')->comment('微信二维码URL');
            $table->string('ticket')->comment('到期前兑换新二维码的票据');
            $table->dateTime('expire_at')->nullable()->comment('到期时间点');
            $table->timestamps();

            $table->foreign('qrcode_id')
                  ->references('id')
                  ->on('we_qrcodes')
                  ->onDelete('cascade');

            $table->index(['qrcode_id', 'scene_str']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_qrcode_details');
        Schema::dropIfExists('we_qrcodes');
    }
}
