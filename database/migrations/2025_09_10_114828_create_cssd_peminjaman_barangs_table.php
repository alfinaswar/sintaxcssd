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
            $table->string('Kode')->unique();
            $table->string('KodeRS', 20)->nullable();

            $table->dateTime('TanggalPinjam')->nullable();
            $table->dateTime('TanggalKembali')->nullable();

            $table->string('NamaPeminjam')->nullable();
            $table->string('RuanganPeminjam')->nullable();

            $table->string('NamaPenerima')->nullable();
            $table->string('RuanganPenerima')->nullable();

            $table->enum('StatusPeminjaman', [
                'Diajukan',
                'Disetujui',
                'Ditolak',
                'Dipinjam',
                'Dikembalikan',
                'Gantung',
            ])->default('Diajukan');

            $table->dateTime('DiajukanPada')->nullable();
            $table->dateTime('DisetujuiPada')->nullable();
            $table->string('DisetujuiOleh')->nullable();
            $table->string('CreatedBy')->nullable();
            $table->string('UpdatedBy')->nullable();
            $table->string('UserCreated')->nullable();
            $table->string('UserUpdated')->nullable();
            $table->text('Keterangan')->nullable();
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
        Schema::dropIfExists('cssd_peminjaman_barangs');
    }
}
