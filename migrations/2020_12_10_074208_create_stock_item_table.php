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
            $table->uuid('id')->primary();

            $table->integer('quantity');
            $table->float('unitPrice');
            $table->string('currency', 3);

            $table->uuid('stockId');
            $table->foreign('stockId')->references('id')->on('stock');

            $table->timestamp('createdAt');
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
