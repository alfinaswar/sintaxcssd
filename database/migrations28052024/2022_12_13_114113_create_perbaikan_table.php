<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerbaikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perbaikan')->nullable();
            $table->string('nama_perangkat')->nullable();
            $table->string('jenis_perangkat')->nullable();
            $table->string('user_pemilik')->nullable();
            $table->string('departemen')->nullable();
            $table->text('masalah')->nullable();
            $table->enum('status', [0, 1])->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('perbaikan');
    }
}
