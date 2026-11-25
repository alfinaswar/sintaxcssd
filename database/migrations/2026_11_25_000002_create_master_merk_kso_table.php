<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterMerkKsoTable extends Migration
{
    public function up()
    {
        Schema::create('master_merk_kso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_alat_kso_id')->constrained('master_alat_kso')->onDelete('cascade');
            $table->string('nama');
            $table->string('nama_rs')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_merk_kso');
    }
}
