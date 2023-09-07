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
        Schema::table('damage_items', function (Blueprint $table) {
            $table->integer('point')->nullable()->after('item_code')->nullable();
            $table->integer('kyat')->nullable()->after('ticket')->nullable();
            $table->integer('purchase_price')->nullable()->after('kyat')->nullable();
            $table->integer('total')->nullable()->after('purchase_price')->nullable();
            $table->text('reason')->nullable()->after('total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
