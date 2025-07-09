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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot tables
        Schema::create('referral_venue', function (Blueprint $table) {
            $table->foreignId('referral_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->primary(['referral_id', 'venue_id']);
        });

        Schema::create('referral_vendor', function (Blueprint $table) {
            $table->foreignId('referral_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->primary(['referral_id', 'vendor_id']);
        });

        Schema::create('referral_catering', function (Blueprint $table) {
            $table->foreignId('referral_id')->constrained()->onDelete('cascade');
            $table->foreignId('catering_id')->constrained()->onDelete('cascade');
            $table->primary(['referral_id', 'catering_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_catering');
        Schema::dropIfExists('referral_vendor');
        Schema::dropIfExists('referral_venue');
        Schema::dropIfExists('referrals');
    }
};
