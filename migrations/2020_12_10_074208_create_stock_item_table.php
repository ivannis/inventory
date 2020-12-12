<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateStockItemTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_item', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('quantity');
            $table->float('unit_price');

            $table->unsignedBigInteger('product_stock_id');
            $table->foreign('product_stock_id')->references('id')->on('product_stock');

            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item');
    }
}
