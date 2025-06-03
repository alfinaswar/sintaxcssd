<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasalahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masalah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_masalah', 100)->nullable();
            $table->string('kode_item', 100);
            $table->string('nama_perangkat', 100)->nullable();
            $table->string('judul', 100)->nullable();
            $table->text('kasus')->nullable();
            $table->string('jumlah_masalah', 50)->nullable();
            $table->string('jenis', 50)->nullable();
            $table->string('tindakan', 100)->nullable();
            $table->time('waktu_pengerjaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('qty')->nullable();
            $table->string('prioritas', 100)->nullable();
            $table->string('UserGroupID', 100)->nullable();
            $table->string('nama_rs', 10);
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
        Schema::dropIfExists('masalah');
    }
}
