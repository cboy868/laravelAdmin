<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPicturesItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('pictures', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')
                ->after('id')
                ->default(1)
                ->comment('1图集，2漫画');
        });

        Schema::table('pictures_item', function (Blueprint $table) {
            $table->unsignedInteger('cartoon_id')
                ->default(0)
                ->after('pictures_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pictures_item', function (Blueprint $table) {
            $table->dropColumn('cartoon_id');
        });

        Schema::table('pictures', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
