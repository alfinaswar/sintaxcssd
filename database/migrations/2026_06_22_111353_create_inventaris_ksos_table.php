<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisKsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris_ksos', function (Blueprint $table) {
            $table->id('id');
            $table->string('KodeBarang')->nullable();
            $table->string('AssetID')->nullable();
            $table->string('Nama', 255)->nullable();
            $table->string('Merk', 255)->nullable();
            $table->string('Tipe', 255)->nullable();
            $table->string('NoSn', 150)->nullable();
            $table->string('Vendor', 150)->nullable();
            $table->date('TanggalKerjasama')->nullable();
            $table->date('AkhirKerjasama')->nullable();
            $table->string('NamaRS', 20)->nullable();
            $table->string('Departemen', 255)->nullable();
            $table->string('Unit', 255)->nullable();
            $table->enum('Pengguna', ['Medis', 'Non Medis'])->nullable();
            $table->string('Gambar', 255)->nullable();
            $table->date('TglKalibrasi')->nullable();
            $table->date('TglExpire')->nullable();
            $table->string('Dokumen', 255)->nullable();
            $table->boolean('IsKalibrasi')->nullable();
            $table->string('Manualbook', 255)->nullable();
            $table->string('Klasifikasi', 255)->nullable();
            $table->text('Keterangan')->nullable();
            $table->string('UserCreate', 255)->nullable();
            $table->string('UserUpdate', 255)->nullable();
            $table->string('UserDelete', 255)->nullable();
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
        Schema::dropIfExists('inventaris_ksos');
    }
}
