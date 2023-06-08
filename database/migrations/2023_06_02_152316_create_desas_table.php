<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesasTable extends Migration
{
    public function up()
    {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_desa', 10)->unique();
            $table->string('kode_kec', 7)->index();
            $table->string('kode_kab', 4)->index();
            $table->string('kode_prov', 2)->index();
            $table->string('nama_desa', 50);

            // Add foreign key constraints
            $table->foreign('kode_kec')->references('kode_kec')->on('kecamatans');
            $table->foreign('kode_kab')->references('kode_kab')->on('kabupatens');
            $table->foreign('kode_prov')->references('kode_prov')->on('provinsis');
        });


    }

    public function down()
    {
        Schema::dropIfExists('desas');
    }
}
