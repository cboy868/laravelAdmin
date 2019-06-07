<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Store extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //商户等级管理
        Schema::create('store_level', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_limit');
            $table->unsignedInteger('album_limit');
            $table->unsignedInteger('space_limit');
            $table->unsignedTinyInteger('is_confirm')->default(1);
            $table->unsignedTinyInteger('level')->default(0);
            $table->unsignedInteger('cost')->default(0)->comment('费用，分为单位');
            $table->text('intro');
        });

        //商户管理
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('level_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('level_id')
                ->references('id')
                ->on('store_level')
                ->onDelete('cascade');
        });

        //商户费用管理
        Schema::create('store_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('price');
            $table->string('remark', 200);
            $table->unsignedTinyInteger('state');
            $table->timestamp('cost_at')->nullable();

            $table->foreign('store_id')
                ->references('id')
                ->on('store')
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
        Schema::dropIfExists('store_level');
        Schema::dropIfExists('store');
        Schema::dropIfExists('store_cost');
    }
}
