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
        Schema::create('medicine_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_available')->default(true);
            $table->string('notes')->nullable();
            $table->bigInteger('offer_qty')->nullable();
            $table->bigInteger('offer_free_qty')->nullable();
            $table->decimal('price', 10, 2)->nullable();


            $table->timestamps();
            $table->unique(['medicine_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_user');
    }
};
