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
        Schema::create('damage', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('outlet_id')->default(0);
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->string('name')->nullable();
            $table->string('amount')->nullable();
            $table->string('action')->nullable();
            $table->string('error')->nullable();
            $table->string('distination')->nullable();
            $table->string('damage_no')->nullable();
            $table->string('column1')->nullable();                    
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
        Schema::dropIfExists('damage');
    }
};
