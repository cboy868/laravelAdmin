<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatSubMerchant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_sub_merchant', function (Blueprint $table) {
            $table->increments('id');

            $table->string('mch_id');
            $table->string('mch_name')->nullable();
            $table->string('mch_key');
            $table->unsignedTinyInteger('is_sub')->default(0);//类型0主账号 1子商户号
            $table->text('cert_content')->nullable();
            $table->text('key_content')->nullable();
            $table->string('charset', 20)->default('UTF-8');
            $table->string('sign_type', 20)->default('MD5');

            $table->softDeletes();
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
        Schema::dropIfExists('wechat_sub_merchant');
    }
}
