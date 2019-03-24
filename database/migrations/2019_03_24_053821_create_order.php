<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');
            $table->unsignedInteger('user_id');
            $table->string('title')->nullable();
            $table->unsignedInteger('price')->default(0);//分为单位
            $table->unsignedInteger('origin_price')->default(0);
            $table->unsignedTinyInteger('type')->default(0);//定单类型
            $table->unsignedTinyInteger('progres')->default(1);
            $table->text('note')->nullable();//备注
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');
            $table->string('title')->nullable();
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->string('goods_no', 64);
            $table->string('sku_no', 64)->nullable();
            $table->string('sku_name')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('origin_price')->default(0);
            $table->unsignedSmallInteger('num')->default(1);
            $table->dateTime('use_time')->nullable();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('is_refund')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('order')
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
        Schema::dropIfExists('order');
        Schema::dropIfExists('order_goods');
    }
}
