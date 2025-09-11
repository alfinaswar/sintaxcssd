<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdPeminjamanBarangDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_peminjaman_barang_details', function (Blueprint $table) {
            $table->id();
            $table->string('IdPeminjaman')->nullable();
            $table->string('IdAlat')->nullable();
            $table->string('Merk')->nullable();
            $table->string('Tipe')->nullable();
            $table->string('Jumlah')->nullable();
            $table->string('KondisiAlat')->nullable();
            $table->string('Keterangan')->nullable();
            $table->string('UserCreated')->nullable();
            $table->string('UserUpdated')->nullable();
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
        Schema::dropIfExists('cssd_peminjaman_barang_details');
    }
}
