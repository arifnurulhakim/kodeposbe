<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKabupatensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kab', 4)->unique();
            $table->string('kode_prov', 2)->index();
            $table->string('nama_kabupaten', 50);

            // Add foreign key constraint to 'provinsi' table
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
        Schema::dropIfExists('kabupatens');
    }
}
