<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateInventoryHistoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_history', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type');
            $table->integer('quantity');
            $table->float('unitPrice')->nullable();
            $table->string('currency', 3)->nullable();

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
        Schema::dropIfExists('inventory_history');
    }
}
