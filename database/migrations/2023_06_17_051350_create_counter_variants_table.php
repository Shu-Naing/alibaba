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
        Schema::create('counter_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('counter_id');
            $table->unsignedBigInteger('variant_id');
            $table->integer('quantity');
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
        Schema::table('counter_variants', function (Blueprint $table) {
            //
        });
    }
};
