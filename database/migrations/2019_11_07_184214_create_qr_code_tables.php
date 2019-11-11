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
        Schema::create('super_qr_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('app_id', 20)->default('');
            $table->string('title')->default('');
            $table->unsignedTinyInteger('type')->default(1)->comment('类型: 1临时 2永久');
            $table->unsignedInteger('target_num')->default(0)->comment('目标数量');
            $table->unsignedInteger('num')->default(0)->comment('实际数量');
            $table->unsignedTinyInteger('expire_type')->default(1)->comment('过期时间计算方式 1时长 2时间点');
            $table->integer('expire_at')->comment('到期时间点');
            $table->integer('expire_in')->comment('有效时长');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态: 0生成中 1完成');
            $table->timestamps();
        });

        Schema::create('super_qr_code_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('qrcode_id')->default(0)->comment('二维码ID');
            $table->string('batch')->default('')->comment('批次号: ymdHi');
            $table->string('scene_str')->default('')->comment('二维码信息');
            $table->unsignedInteger('scan_num')->default(0)->comment('扫码数量统计');
            $table->unsignedInteger('subscribe_num')->default(0)->comment('扫码关注数量统计');
            $table->string('url')->default('');
            $table->string('ticket')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('super_qr_code');
        Schema::dropIfExists('super_qr_code_detail');
    }
}