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
        Schema::create('outlet_distributes', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('reference_No');
            $table->integer('status')->default(1);
            $table->string('from_outlet');
            $table->string('to_machine');
            $table->integer('counter_machine')->nullable();
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
        Schema::dropIfExists('outlet_distributes');
    }
};
