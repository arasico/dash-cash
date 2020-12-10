<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuyBotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_bot', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('symbol');
            $table->decimal('amount', 32, 16);
            $table->decimal('price', 32, 16);
            $table->decimal('total', 32, 16);
            $table->decimal('price_change_percent_buy', 32, 16);
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
        Schema::dropIfExists('buy_bot');
    }
}
