<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variations', function (Blueprint $table) {
            $table->integer('points')->nullable()->change();
            $table->integer('tickets')->nullable()->change();
            $table->integer('kyat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variations', function (Blueprint $table) {
            //
        });
    }
};
