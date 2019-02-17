<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNovel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //作者
        Schema::create('novel_author', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 255);//标题
            $table->string('summary');//简介
            $table->unsignedSmallInteger('quantity');//作品数量

            $table->timestamps();
        });

        //标题，作者，内容简介，推荐指数，点赞数，字数，分类
        Schema::create('novel', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 255);//标题
            $table->unsignedSmallInteger('category_id');//分类
            $table->unsignedInteger('author_id');//作者
            $table->string('summary');//内容简介
            $table->unsignedSmallInteger('rate');//推荐指数
            $table->unsignedInteger('like');//点赞数
            $table->unsignedInteger('words');//字数
            $table->unsignedSmallInteger('type');//类型 精品、历史、都市、玄幻等

            $table->timestamps();

            $table->foreign('author_id')
                ->references('id')
                ->on('novel_author')
                ->onDelete('cascade');
        });

        //内容表
        Schema::create('novel_content', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('novel_id');//主id
            $table->unsignedSmallInteger('chapter');//章节排序
            $table->unsignedSmallInteger('page');//每一章的分页
            $table->string('title');//章节标题
            $table->text('content');//具体内容

            $table->foreign('novel_id')
                ->references('id')
                ->on('novel')
                ->onDelete('cascade');

            $table->index(['novel_id', 'chapter', 'page'],
                'model_movel_chapter_primary');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('novel');
        Schema::dropIfExists('novel_content');
        Schema::dropIfExists('novel_author');
    }
}
