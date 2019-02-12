<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('title');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('title');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}
