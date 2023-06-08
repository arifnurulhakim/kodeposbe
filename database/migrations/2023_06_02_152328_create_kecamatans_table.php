<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKecamatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kec', 7)->unique();
            $table->string('kode_kab', 4)->index();
            $table->string('kode_prov', 2)->index();
            $table->string('nama_kecamatan', 50);

            // Add foreign key constraints
            $table->foreign('kode_kab')->references('kode_kab')->on('kabupatens');
            $table->foreign('kode_prov')->references('kode_prov')->on('provinsis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kecamatans');
    }
}
