<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pay', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('order_id');
            $table->string('title')->nullable();
            $table->string('order_no', 255);
            $table->string('local_trade_no', 255);
            $table->string('trade_no', 255)->nullable();
            $table->unsignedInteger('total_fee');
            $table->unsignedInteger('total_pay')->default(0);
            $table->string('pay_method')->comment('支付方式')->nullable();
            $table->unsignedTinyInteger('pay_result')->default(1)->comment('支付状态 1初始化');
            $table->timestamp('paid_at')->nullable();
            $table->unsignedInteger('checkout_at')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1)->comment('支付单状态 1正常 -1删除');//状态
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
        Schema::dropIfExists('order_pay');
    }
}
