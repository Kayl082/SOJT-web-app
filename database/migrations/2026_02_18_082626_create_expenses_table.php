<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained('stores')
                ->cascadeOnDelete();

            $table->string('expense_type', 100);
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->string('receipt_img', 500)->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('expense_date');
            $table->index('expense_type');
        });
    }

    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};
