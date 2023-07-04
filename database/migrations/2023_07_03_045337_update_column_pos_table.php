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
    //   DB::statement("ALTER TABLE `pos` CHANGE `payment_type` `payment_type` ENUM('points', 'tickets','kyat');");
    DB::statement("ALTER TABLE pos CHANGE payment_type payment_type ENUM('points', 'tickets', 'kyat')");



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE pos CHANGE payment_type payment_type ENUM('point', 'ticket', 'kyat')");

    }
};
