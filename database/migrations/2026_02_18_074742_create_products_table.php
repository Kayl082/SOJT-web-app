<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('product_name', 255);
            $table->text('description')->nullable();

            $table->string('barcode', 100)->nullable();
            $table->string('image_url', 500)->nullable();

            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['store_id', 'product_name']);
            $table->index('barcode');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
