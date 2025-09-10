<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdPeminjamanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('Kode')->nullable();
            $table->dateTime('Tanggal')->nullable();
            $table->string('NamaPeminjam')->nullable();
            $table->string('RuanganPeminjam')->nullable();
            $table->string('NamaPenerima')->nullable();
            $table->string('RuanganPenerima')->nullable();
            $table->enum('StatusPeminjaman', ['Y', 'N'])->nullable();
            // $table->string('', 100)->nullable()->default('text');
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
        Schema::dropIfExists('cssd_peminjaman_barangs');
    }
}
