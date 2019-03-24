<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unionid')->nullable();
            $table->string('openid');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('nickname')->nullable();
            $table->unsignedSmallInteger('sex')->nullable();
            $table->string('language', 20)->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string("headimgurl")->nullable();
            $table->unsignedTinyInteger('subscribe')->default(0);
            $table->unsignedInteger('subscribe_at')->nullable();
            $table->string('mobile', 50)->nullable();
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
        Schema::dropIfExists('wechat_user');
    }
}
