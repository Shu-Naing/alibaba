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
        Schema::create('outlet_level_overviews', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer("opening_qty")->default(0);
            $table->integer("receive_qty")->default(0);
            $table->integer("issued_qty")->default(0);
            $table->unsignedBigInteger("outlet_id")->default(0);
            $table->string("item_code");
            $table->integer("balance")->default(0);
            $table->boolean("is_check")->default(0);
            $table->integer("physical_qty")->default(0);
            $table->integer("difference_qty")->default(0);
            $table->unsignedBigInteger("created_by");
            $table->unsignedBigInteger("updated_by")->nullable();
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
        Schema::dropIfExists('outlet_level_overviews');
    }
};
