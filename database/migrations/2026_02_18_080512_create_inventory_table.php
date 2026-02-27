<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->unsignedInteger('stock_level')->default(0);
            $table->unsignedInteger('reorder_level')->default(10);
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->unique(['store_id', 'product_id']);
            $table->index(['store_id', 'is_available']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('inventory');
    }
};
