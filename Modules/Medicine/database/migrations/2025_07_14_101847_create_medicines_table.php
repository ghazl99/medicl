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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(); // الصنف
            $table->text('composition')->nullable(); // التركيب
            $table->string('form')->nullable(); // الشكل
            $table->string('company')->nullable(); // الشركة
            $table->text('note')->nullable(); // ملاحظات
            $table->decimal('net_dollar_old', 10, 2)->nullable(); // نت دولار حالي
            $table->decimal('public_dollar_old', 10, 2)->nullable(); // عموم دولار حالي
            $table->decimal('net_dollar_new', 10, 2)->nullable(); // النت دولار الجديد
            $table->decimal('public_dollar_new', 10, 2)->nullable(); // العموم دولار الجديد
            $table->decimal('net_syp', 10, 2)->nullable(); // نت سوري
            $table->decimal('public_syp', 10, 2)->nullable(); // عموم سوري
            $table->text('note_2')->nullable(); // ملاحظات (الثانية)
            $table->string('price_change_percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
