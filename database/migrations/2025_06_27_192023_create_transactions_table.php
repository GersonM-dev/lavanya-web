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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('catering_id');
            $table->unsignedBigInteger('venue_id');
            $table->date('transaction_date');
            $table->decimal('total_estimated_price', 15, 2);
            $table->string('status');
            $table->text('notes')->nullable();
            $table->decimal('catering_total_price', 15, 2)->nullable();
            $table->decimal('total_buffet_price', 15, 2)->nullable();
            $table->decimal('total_gubugan_price', 15, 2)->nullable();
            $table->decimal('total_dessert_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
