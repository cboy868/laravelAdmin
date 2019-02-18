<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAccountsWechat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accounts_wechat', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('unionid', 255);
            $table->string('openid', 255);
            $table->string('nickname');
            $table->string('remark');
            $table->tinyInteger('sex');
            $table->string('language', 100);
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('headimgurl');
            $table->tinyInteger('subscribe');
            $table->unsignedInteger('subscribe_at');
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
        Schema::drop('user_accounts_wechat');
    }
}
