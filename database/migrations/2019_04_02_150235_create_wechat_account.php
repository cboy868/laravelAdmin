<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_account', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('merchant_id');
            $table->string('appid');
            $table->string('secret');
            $table->string('name')->nullable();
            $table->string('token');
            $table->string('aes_key')->nullable();
            $table->string('mch_id')->nullable()->comment('商户id');
            $table->unsignedTinyInteger('type')->default(0);//类型如公众号，小程序等
            $table->softDeletes();
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
        Schema::dropIfExists('wechat_merchant');
    }
}
