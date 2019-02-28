<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePictures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //图册分类
        Schema::create('pictures_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedTinyInteger('type')->default(0);//类型，0免等
            $table->softDeletes();//软删除
            $table->timestamps();
        });

        //图册
        Schema::create('pictures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('author')->nullable();//作者
            $table->string('name');//图册名
            $table->unsignedInteger('thumb')->nullable();//封面
            $table->unsignedSmallInteger('sort')->default(0);//排序
            $table->text('intro');//介绍
            $table->unsignedSmallInteger('num')->default(0);//数量
            $table->unsignedSmallInteger('views')->default(0);//查看次数
            $table->unsignedSmallInteger('comms')->default(0);//评论次数
            $table->unsignedInteger('created_by');//添加人
            $table->unsignedTinyInteger('recommend')->default(0);//推荐

            $table->softDeletes();//软删除
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('pictures_category')
                ->onDelete('cascade');
        });


        //图片
        Schema::create('pictures_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pictures_id');
            $table->string('title');//标题
            $table->string('path');//路径
            $table->string('name');//处理后的真实图片名,加path后为图片地址
            $table->string('ext', 64);
            $table->unsignedSmallInteger('sort');
            $table->text('intro')->nullable();//图片介绍
            $table->softDeletes();//软删除
            $table->timestamps();

            $table->foreign('pictures_id')
                ->references('id')
                ->on('pictures')
                ->onDelete('cascade');
        });


        //图片与人
        Schema::create('pictures_user_rel', function (Blueprint $table) {
            $table->unsignedInteger('pictures_id');//相册id
            $table->unsignedInteger('user_id');//品牌id
            $table->softDeletes();//软删除
            $table->timestamps();


            $table->foreign('pictures_id')->references('id')->on('pictures')
                ->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');

            $table->primary(['pictures_id' , 'user_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pictures_category');
        Schema::dropIfExists('pictures');
        Schema::dropIfExists('pictures_item');
        Schema::dropIfExists('pictures_user_rel');
    }
}
