<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SellBotHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_bot_history', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('symbol');
            $table->decimal('amount', 32, 16);
            $table->decimal('price', 32, 16);
            $table->decimal('total', 32, 16);
            $table->decimal('sell_amount', 32, 16);
            $table->decimal('sell_price', 32, 16);
            $table->decimal('sell_total', 32, 16);
            $table->decimal('profit_percent', 32, 16);
            $table->decimal('price_change_percent_buy', 32, 16);
            $table->decimal('price_change_percent_sell', 32, 16);
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
        Schema::dropIfExists('sell_bot_history');
    }
}
