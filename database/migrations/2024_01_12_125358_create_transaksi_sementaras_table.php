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
        Schema::create('transaksi_sementaras', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->integer('barang_id');
            $table->float('harga_jual');
            $table->bigInteger('jumlah');
            $table->float('diskon');
            $table->float('total');
            $table->string('pasien')->nullable();
            $table->string('diagnosa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_sementaras');
    }
};
