<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商品类型表，用于管理属性
        Schema::create('product_attribute_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid');
            $table->tinyInteger('level');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->text('intro')->nullable();//详细介绍
            $table->timestamps();
            $table->softDeletes();//软删除
        });

        //属性分组
        Schema::create('product_attribute_group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_category_id')->index();
            $table->string('name', 100);
            $table->text('intro')->nullable();//详细介绍
            $table->softDeletes();//软删除

            $table->foreign('attribute_category_id')
                ->references('id')
                ->on('product_attribute_category')
                ->onDelete('cascade');
        });

        //属性key
        Schema::create('product_attribute_key', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_category_id')->index();
            $table->unsignedInteger('attribute_group_id')->index();
            $table->string('name', 100);
            $table->unsignedTinyInteger('type')->comment('关键属性、销售属性等');//类型
            $table->text('intro')->nullable();//介绍
            $table->softDeletes();//软删除

            $table->foreign('attribute_category_id')
                ->references('id')
                ->on('product_attribute_category')
                ->onDelete('cascade');

            $table->foreign('attribute_group_id')
                ->references('id')
                ->on('product_attribute_group')
                ->onDelete('cascade');
        });

        //属性值
        Schema::create('product_attribute_value', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_id');
            $table->string('value', 100);
            $table->string('image', 200)->nullable();

            $table->foreign('attribute_id')
                ->references('id')
                ->on('product_attribute_key')
                ->onDelete('cascade');
        });

        //分类
        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_category_id');
            $table->unsignedInteger('pid')->default(0);
            $table->unsignedTinyInteger('level');
            $table->string('code', 100);
            $table->string('name', 100);
            $table->unsignedTinyInteger('is_leaf')->default(0);
            $table->unsignedTinyInteger('is_view')->default(1);
            $table->unsignedTinyInteger('sort')->default(0);
            $table->text('intro')->nullable();//详细介绍
            $table->timestamps();
            $table->softDeletes();//软删除

            $table->foreign('attribute_category_id')
                ->references('id')
                ->on('product_attribute_category')
                ->onDelete('cascade');
        });

        //品牌管理
        Schema::create('product_brand', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid');
            $table->string('cn_name', 100)->nullable();
            $table->string('en_name', 100)->nullable();
            $table->string('icon', 200);
            $table->text('intro')->nullable();
            $table->timestamps();
        });

        //产品管理
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('brand_id');
            $table->string('product_no', 64);
            $table->string('pinyin', 100);
            $table->string('title', 200);
            $table->string('subtitle', 200)->nullable();
            $table->text('body')->nullable();
            $table->unsignedTinyInteger('status')->default(1);//上下架状态等
            $table->unsignedInteger('max_price')->default(0);
            $table->unsignedInteger('min_price')->default(0);
            $table->timestamps();
            $table->softDeletes();//软删除

            $table->foreign('category_id')
                ->references('id')
                ->on('product_category')
                ->onDelete('cascade');

            $table->foreign('store_id')
                ->references('id')
                ->on('store')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('product_brand')
                ->onDelete('cascade');
        });

        Schema::create('product_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('product_id');
            $table->string('sku_no', 64);
            $table->string('title', 200);
            $table->text('rels');
            $table->unsignedInteger('num');
            $table->unsignedInteger('price');

            $table->timestamps();
            $table->softDeletes();//软删除


            $table->foreign('product_id')
                ->references('id')
                ->on('product')
                ->onDelete('cascade');

            $table->foreign('store_id')
                ->references('id')
                ->on('store')
                ->onDelete('cascade');
        });

        //属性键值关系
        Schema::create('product_attribute_rel', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('sku_id');
            $table->unsignedInteger('attribute_id');
            $table->unsignedInteger('attribute_value_id');


            $table->foreign('product_id')
                ->references('id')
                ->on('product')
                ->onDelete('cascade');

            $table->foreign('sku_id')
                ->references('id')
                ->on('product_sku')
                ->onDelete('cascade');

            $table->foreign('attribute_id')
                ->references('id')
                ->on('product_attribute_key')
                ->onDelete('cascade');

            $table->foreign('attribute_value_id')
                ->references('id')
                ->on('product_attribute_value')
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
        Schema::dropIfExists('product_attribute_rel');
        Schema::dropIfExists('product_sku');
        Schema::dropIfExists('product');
        Schema::dropIfExists('product_brand');
        Schema::dropIfExists('product_category');

        Schema::dropIfExists('product_attribute_value');
        Schema::dropIfExists('product_attribute_key');
        Schema::dropIfExists('product_attribute_category');
    }
}
