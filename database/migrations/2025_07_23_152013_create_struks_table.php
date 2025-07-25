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
        Schema::create('struks', function (Blueprint $table) {
            $table->id();
            $table->string('status');                 // "Berhasil"
            $table->dateTime('tgl_bayar');           // 2024-12-03 09:20:43
            $table->string('id_transaksi');          // 8192038475
            $table->string('no_meter');              // 32177622878
            $table->string('id_pelanggan');          // 115530114119
            $table->string('nama');                  // BALAI DESA
            $table->string('tarif_daya');            // S1/900 VA
            $table->string('no_reff');               // 5F1A203B94DC...
            $table->unsignedInteger('rp_bayar');     // 53000
            $table->string('token');                 // 9283 5647 1829 3048 1029
            $table->timestamps();                    // created_at & updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struks');
    }
};
