<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_item', 100)->nullable();
           
            $table->string('assetID', 100)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('real_name', 100)->nullable();
            $table->string('no_inventaris', 100)->nullable();
            $table->string('no_sn', 100)->nullable();
            $table->dateTime('tanggal_beli')->nullable();
            $table->string('nama_rs', 100)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->string('pengguna', 100)->nullable();
            $table->string('gambar', 100)->nullable();
            $table->date('tgl_kalibrasi')->nullable();
            $table->date('tgl_expire')->nullable();
            $table->string('dokumen', 100)->nullable();
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
        Schema::dropIfExists('data_inventaris');
    }
}
