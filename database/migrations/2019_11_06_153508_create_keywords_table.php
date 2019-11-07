<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keywords');
    }
}
