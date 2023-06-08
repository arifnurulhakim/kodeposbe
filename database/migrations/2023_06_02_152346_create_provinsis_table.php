<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinsisTable extends Migration
{
    public function up()
    {
        Schema::create('provinsis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prov', 2)->unique();
            $table->string('nama_provinsi', 50);
        });
    }

    public function down()
    {
        Schema::dropIfExists('provinsis');
    }
}

