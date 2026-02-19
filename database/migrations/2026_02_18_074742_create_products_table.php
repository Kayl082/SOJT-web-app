<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('product_name', 255);
            $table->text('description')->nullable();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('barcode', 100)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('barcode');
            $table->index('product_name');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
