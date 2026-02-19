<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_staff', function (Blueprint $table) {
            if (Schema::hasColumn('store_staff', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('store_staff', function (Blueprint $table) {
            if (!Schema::hasColumn('store_staff', 'role')) {
                $table->enum('role', ['staff', 'manager'])
                      ->default('staff')
                      ->after('user_id');
            }
        });
    }
};
