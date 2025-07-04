<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenghapusanAsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghapusan_asets', function (Blueprint $table) {
            $table->id();
            $table->string('NomorPengajuan')->nullable();
            $table->string('Departemen')->nullable();
            $table->string('Unit')->nullable();
            $table->date('Tanggal')->nullable();
            $table->enum('Status', ['pengajuan', 'disetujui', 'ditolak', 'proses'])->default('pengajuan');
            $table->string('DiajukanOleh')->nullable();
            $table->string('Sign1')->nullable();
            $table->string('Sign2')->nullable();
            $table->string('Sign3')->nullable();
            $table->string('Sign4')->nullable();
            $table->string('KodeRS')->nullable();
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
        Schema::dropIfExists('penghapusan_asets');
    }
}
