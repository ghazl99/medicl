<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacist_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('supplier_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->enum('status', ['قيد الانتظار', 'مرفوض جزئياً', 'تم التأكيد', 'تم التسليم', 'ملغي'])->default('قيد الانتظار');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
