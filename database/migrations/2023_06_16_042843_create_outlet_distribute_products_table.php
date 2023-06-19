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
        Schema::create('outlet_distribute_products', function (Blueprint $table) {
            $table->id();
            $table->integer('outlet_distribute_id');
            $table->integer('variant_id');
            $table->integer('quantity');
            $table->integer('purchased_price');
            $table->integer('subtotal');
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlet_distribute_products');
    }
};
