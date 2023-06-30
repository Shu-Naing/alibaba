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
        Schema::table('outlet_stock_overviews', function (Blueprint $table) {
            $table->boolean('is_check')->default(0)->after('balance');
            $table->integer('physical_qty')->default(0)->after('is_check');
            $table->integer('difference_qty')->default(0)->after('physical_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlet_stock_overviews');
    }
};
