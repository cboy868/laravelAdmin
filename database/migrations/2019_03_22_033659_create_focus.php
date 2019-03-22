<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFocus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('focus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->unsignedInteger('appid')->default(1);
            $table->string('pos', 100);//焦点图位置
            $table->text('intro')->nullable();
        });

        Schema::create('focus_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fid');
            $table->string('path');//图片路径
            $table->string('link');
            $table->string('title')->nullable();//可以写标题
            $table->text('intro')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);

            $table->foreign('fid')
                ->references('id')
                ->on('focus')
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
        Schema::dropIfExists('focus');
        Schema::dropIfExists('focus_item');
    }
}
