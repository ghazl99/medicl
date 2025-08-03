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
        Schema::create('offer_supplier_medicine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_user_id')->constrained('medicine_user')->onDelete('cascade');

            $table->integer('offer_buy_quantity');
            $table->integer('offer_free_quantity');

            $table->date('offer_start_date');
            $table->date('offer_end_date');

            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_supplier_medicine_offers');
    }
};
