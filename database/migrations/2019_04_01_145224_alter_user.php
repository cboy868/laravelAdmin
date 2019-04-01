<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //添加会员注册时间，及登录次数
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('login_times')
                ->default(0)
                ->comment("登录次数");
            $table->timestamp('register_time')
                ->default(\DB::raw('CURRENT_TIMESTAMP'))
                ->comment('注册时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login_times');
            $table->dropColumn('register_time');
        });
    }
}
