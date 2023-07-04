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
        Schema::create('purchased_price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->foreign('variation_id')
                  ->references('id')->on('variations')->onDelete('cascade');
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
        Schema::dropIfExists('purchased_price_histories');
    }
};
