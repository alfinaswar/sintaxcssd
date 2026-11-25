<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterAlatKsoTable extends Migration
{
    public function up()
    {
        Schema::create('master_alat_kso', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_rs')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_alat_kso');
    }
}
