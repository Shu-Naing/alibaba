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
        Schema::create('outlet_stock_overviews', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('opening_qty');
            $table->integer('received_qty');
            $table->integer('issued_qty');
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('machine_id');
            $table->integer('item_code');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('outlet_stock_overviews');
    }
};
