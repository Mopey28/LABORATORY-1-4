<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('stock_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('old_stock');
            $table->integer('current_stock');
            $table->integer('added_stock');
            $table->string('action'); // 'added' or 'removed'
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_history');
    }
}
