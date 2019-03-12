<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商品类型
        Schema::create('goods_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');//类型名
            $table->softDeletes();
            $table->timestamps();
        });

        //商品规格属性表
        Schema::create('goods_attribute_key', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id')->index();
            $table->string('name');
            $table->unsignedTinyInteger('is_multi')->default(0);//是否可多选
            $table->unsignedTinyInteger('is_spec')->default(0);//是否是规格
            $table->text('content')->nullable();//详细介绍
            $table->softDeletes();//软删除
            $table->foreign('type_id')
                ->references('id')
                ->on('goods_type')
                ->onDelete('cascade');
        });

        //商品规格属性值
        Schema::create('goods_attribute_value', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('attr_id')->index();
            $table->string('value');
            $table->string('thumb')->nullable();//图片
            $table->softDeletes();//软删除
            $table->foreign('type_id')
                ->references('id')
                ->on('goods_type')
                ->onDelete('cascade');
        });

        //商品分类表
        Schema::create('goods_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0);
            $table->unsignedInteger('type_id')->default(0);//叶子节点才需要分配
            $table->unsignedSmallInteger('level')->default(1);//树级
            $table->string('code', 100);
            $table->string('name', 255);
            $table->text('intro')->nullable();//分类介绍
            $table->unsignedTinyInteger('sort')->default(0);//排序
            $table->unsignedTinyInteger('is_leaf')->default(1);//是否根节点
            $table->unsignedTinyInteger('is_show')->default(1);//前台是否展示
            $table->string('thumb')->nullable();//分类图片
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('goods_type')
                ->onDelete('cascade');
        });

        //商品品牌
        Schema::create('goods_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();//品牌名
            $table->string('cn_name')->index()->nullable();//中文品牌名
            $table->string('logo')->nullable();//logo
            $table->softDeletes();//软删除
            $table->timestamps();
        });

        //品牌也分类的关系多对多，需要加此中间表
        Schema::create('goods_category_brand', function (Blueprint $table) {
            $table->unsignedInteger('cid')->comment('分类id');//分类id
            $table->unsignedInteger('brand_id');//品牌id
            $table->softDeletes();//软删除

            $table->foreign('cid')->references('id')->on('goods_category')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('brand_id')->references('id')->on('goods_brand')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->primary(['cid' , 'brand_id']);
        });

        //商品
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cid')->index();//分类id
            $table->unsignedInteger('brand_id');//品牌
            $table->string('pinyin')->index();
            $table->string('goods_no')->unique();//商品编号
            $table->string('name');//商品名
            $table->string('subtitle')->nullable();
            $table->string('thumb')->nullable();
            $table->string('keywords')->nullable();//关键词
            $table->text('description')->nullable();//描述，有利seo
            $table->text('content')->nullable();
            $table->string('unit')->nullable();//单位
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedInteger('min_price');//最低价单位分
            $table->unsignedInteger('max_price');//最高价
            $table->tinyInteger('status')->default(1);//上下架 1 上架 -1下架
            $table->unsignedTinyInteger('is_recommend')->default(1);//是否推荐
            $table->unsignedTinyInteger('is_show')->default(1);//是否前台展示
            $table->softDeletes();//软删除
            $table->timestamps();

            $table->foreign('cid')
                ->references('id')
                ->on('goods_category')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('goods_brand')
                ->onDelete('cascade');
        });

        //商品规则属性与值关联
        Schema::create('goods_attribute_rel', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id')->index();
            $table->unsignedInteger('cid')->index();
            $table->unsignedInteger('goods_id')->index();
            $table->unsignedInteger('attribute_key_id');
            $table->unsignedInteger('attribute_value_id');
            $table->string('value')->nullable();//值,此值应该是可以在添加商品时手动添加
            $table->softDeletes();//软删除
            $table->foreign('type_id')
                ->references('id')
                ->on('goods_type')
                ->onDelete('cascade');

            $table->foreign('cid')
                ->references('id')
                ->on('goods_category')
                ->onDelete('cascade');

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
                ->onDelete('cascade');

            $table->foreign('attribute_key_id')
                ->references('id')
                ->on('goods_attribute_key')
                ->onDelete('cascade');

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('goods_attribute_value')
                ->onDelete('cascade');

        });

        //商品规则属性与值关联
        Schema::create('goods_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('goods_id')->index();
            $table->string('sku_no')->unique();//sku编号 不可重复
            $table->unsignedInteger('quantity')->default(1);//数量
            $table->unsignedInteger('price');
            $table->unsignedInteger('original_price');
            $table->string('title');//标题
            $table->string('key_value');//属性:属性值串,属性:属性值串
            $table->softDeletes();//软删除
            $table->timestamps();

            $table->foreign('goods_id')
                ->references('id')
                ->on('goods')
                ->onDelete('cascade');
        });

        //商品图片
        Schema::create('goods_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('res_name');
            $table->unsignedInteger('res_id');
            $table->string('title');//标题
            $table->string('path');//路径
            $table->string('name');//真实图片名
            $table->string('ext', 64);//扩展
            $table->unsignedInteger('sort')->default(0);//排序
            $table->text('intro')->nullable();//描述
            $table->softDeletes();//软删除
            $table->timestamps();

            $table->index(['res_name', 'res_id'], 'model_res_name_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_type');
        Schema::dropIfExists('goods_attribute_key');
        Schema::dropIfExists('goods_attribute_value');
        Schema::dropIfExists('goods_category');
        Schema::dropIfExists('goods_brand');
        Schema::dropIfExists('goods');
        Schema::dropIfExists('goods_attribute_rel');
        Schema::dropIfExists('goods_sku');
        Schema::dropIfExists('goods_images');
        Schema::dropIfExists('goods_category_brand');
    }
}
