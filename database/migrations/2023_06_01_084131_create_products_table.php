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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')
                  ->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                  ->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id') 
                  ->references('id')->on('units')->onDelete('cascade');
            $table->string('company_name');
            $table->string('country');
            $table->date('received_date');
            $table->date('expired_date');
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
        Schema::dropIfExists('products');
    }
};
