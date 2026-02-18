<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->restrictOnDelete();

            $table->string('order_number', 50)->unique();
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])
                ->default('pending');
            $table->decimal('total_amount', 10, 2);

            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            $table->timestamp('pickup_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['store_id', 'status', 'created_at']);
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
