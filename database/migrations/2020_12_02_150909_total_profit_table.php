<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TotalProfitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_profit', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->string('symbol');
            $table->decimal('profit', 32, 16);
            $table->decimal('profit_percent', 32, 16);
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
        Schema::dropIfExists('total_profit');
    }
}
