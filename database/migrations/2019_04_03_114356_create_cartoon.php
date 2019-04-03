<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartoon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures_cartoon', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pictures_id');
            $table->unsignedSmallInteger('chapter')->comment('章节，从1开始');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('thumb')->nullable();
            $table->text('intro')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pictures_id')
                ->references('id')
                ->on('pictures')
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
        Schema::dropIfExists('pictures_cartoon');
    }
}
