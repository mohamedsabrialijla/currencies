<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->enum('side', ['buy', 'sell']);
            $table->string('type');
            $table->string('symbol');
            $table->double('price');
            $table->double('quantity');
            $table->double('commission')->default(0);
            $table->double('stop_price')->nullable();
            $table->double('price_change_percent')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('order_status')->nullable();
            $table->json('response')->nullable();
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
        Schema::dropIfExists('trades');
    }
}
