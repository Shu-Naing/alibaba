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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                  ->references('id')->on('products')->onDelete('cascade');
            $table->string('select');
            $table->string('value');
            $table->string('item_code');
            $table->integer('received_qty');
            $table->integer('alert_qty');
            $table->string('purchased_price');
            $table->string('points');
            $table->string('tickets');
            $table->string('kyat');
            $table->string('image');
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('variations');
    }
};
