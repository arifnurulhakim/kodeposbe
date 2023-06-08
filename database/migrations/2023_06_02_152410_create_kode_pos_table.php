<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKodePosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kode_pos', function (Blueprint $table) {
            $table->id();
            $table->string('kode_dagri', 10)->unique();
            $table->string('kode_old', 10);
            $table->string('kode_mod', 10);
            $table->string('kode_new', 10);
            $table->string('kode_desa', 10)->index();
            $table->string('kode_kec', 7)->index();
            $table->string('kode_kab', 4)->index();
            $table->string('kode_prov', 2)->index();

            // Add foreign key constraints
            $table->foreign('kode_desa')->references('kode_desa')->on('desas');
            $table->foreign('kode_kec')->references('kode_kec')->on('kecamatans');
            $table->foreign('kode_kab')->references('kode_kab')->on('kabupatens');
            $table->foreign('kode_prov')->references('kode_prov')->on('provinsis');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kode_pos');
    }
}
