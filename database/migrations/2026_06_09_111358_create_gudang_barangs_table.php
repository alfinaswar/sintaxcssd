<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGudangBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gudang_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_item')->nullable();
            $table->bigInteger('RO2ID')->nullable();
            $table->double('harga')->nullable();
            $table->bigInteger('ROID')->nullable();
            $table->bigInteger('assetID')->nullable();
            $table->string('nama')->nullable();
            $table->string('merk')->nullable();
            $table->string('real_name')->nullable();
            $table->string('no_inventaris')->nullable();
            $table->string('no_sn')->nullable();
            $table->date('tanggal_beli')->nullable();
            $table->string('nama_rs')->nullable();
            $table->string('departemen')->nullable();
            $table->string('unit')->nullable();
            $table->string('pengguna')->nullable();
            $table->string('gambar')->nullable();
            $table->date('tgl_kalibrasi')->nullable();
            $table->date('tgl_expire')->nullable();
            $table->string('dokumen')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('isKalibrasi')->default(false);
            $table->string('manualbook')->nullable();
            $table->string('klasifikasi')->nullable();
            $table->string('UserCreate')->nullable();
            $table->bigInteger('UserId')->nullable();
            $table->string('UpdateName')->nullable();
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
        Schema::dropIfExists('gudang_barangs');
    }
}
