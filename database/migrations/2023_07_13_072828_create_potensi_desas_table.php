<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotensiDesasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('potensi_desas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_dagri')->nullable();
            $table->integer('jumlah_penduduk')->nullable();
            $table->integer('jumlah_fasilitas_pendidikan')->nullable();
            $table->integer('jumlah_fasilitas_ibadah')->nullable();
            $table->integer('jumlah_tempat_wisata')->nullable();
            $table->integer('jumlah_industri_kecil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potensi_desas');
    }
}
