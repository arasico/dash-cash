<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SettingBotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_bot', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('symbol');
            $table->decimal('budget', 32, 16);
            $table->decimal('purchase_amount', 32, 16);
            $table->decimal('buy_percent', 32, 16);
            $table->decimal('sell_percent', 32, 16);
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
        Schema::dropIfExists('setting_bot');
    }
}
