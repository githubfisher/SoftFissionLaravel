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
        Schema::create('we_qrcode', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 20)->default('');
            $table->unsignedBigInteger('rule_id')->default(0);
            $table->string('title')->default('');
            $table->unsignedTinyInteger('type')->default(1)->comment('类型: 1临时 2永久');
            $table->unsignedInteger('target_num')->default(0)->comment('目标数量');
            $table->unsignedInteger('num')->default(0)->comment('实际数量');
            $table->unsignedTinyInteger('expire_type')->default(1)->comment('过期时间计算方式 1时长 2时间点');
            $table->dateTime('expire_at')->nullable()->comment('到期时间点');
            $table->integer('expire_in')->default(0)->comment('有效时长');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态: 0生成中 1完成');
            $table->timestamps();

            $table->index(['app_id', 'rule_id']);
        });

        Schema::create('we_qrcode_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('qrcode_id')->default(0)->comment('二维码ID');
            $table->string('batch', 12)->default('')->comment('批次号: ymdHi');
            $table->string('scene_str')->default('')->comment('二维码信息');
            $table->unsignedInteger('scan_num')->default(0)->comment('扫码数量统计');
            $table->unsignedInteger('subscribe_num')->default(0)->comment('扫码关注数量统计');
            $table->string('url')->default('')->comment('微信二维码URL');
            $table->string('ticket')->default('')->comment('到期前兑换新二维码的票据');
            $table->dateTime('expire_at')->nullable()->comment('到期时间点');
            $table->timestamps();

            $table->foreign('qrcode_id')
                  ->references('id')->on('we_qrcode')
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
        Schema::dropIfExists('we_qrcode_detail');
        Schema::dropIfExists('we_qrcode');
    }
}
