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
        Schema::create('outlet_item_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_item_id');
            $table->foreign('outlet_item_id')
                  ->references('id')->on('outlet_items')->onDelete('cascade');
            $table->string('purchased_price');
            $table->string('points')->nullable();
            $table->string('tickets')->nullable();
            $table->string('kyat')->nullable();
            $table->integer('quantity');
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
        Schema::dropIfExists('outlet_item_data');
    }
};
