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
        Schema::table('caterings', function (Blueprint $table) {
            // 1️⃣ Hapus kolom lama
            $table->dropColumn('harga');

            // 2️⃣ Tambah kolom harga baru
            $table->integer('buffet_price')->nullable()->after('portofolio_link');
            $table->integer('gubugan_price')->nullable()->after('buffet_price');
            $table->integer('dessert_price')->nullable()->after('gubugan_price');
            $table->integer('base_price')->nullable()->after('dessert_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caterings', function (Blueprint $table) {
            //
        });
    }
};
