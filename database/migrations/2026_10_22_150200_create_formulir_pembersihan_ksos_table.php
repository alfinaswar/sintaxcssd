<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulirPembersihanKsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulir_pembersihan_ksos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('inventaris_kso_id')->nullable();
            $table->string('AssetID', 100)->nullable();
            $table->string('idUser', 100)->nullable();
            $table->dateTime('Tanggal')->nullable();
            $table->enum('Status', ['Bersih', 'Tidak', 'Lainnya'])->nullable();
            $table->text('Before')->nullable();
            $table->text('After')->nullable();
            $table->string('Keterangan', 255)->nullable();
            $table->string('NamaRS', 20)->nullable();
            $table->string('createdBy', 100)->nullable();
            $table->timestamps();

            $table->index('inventaris_kso_id', 'formulir_pembersihan_ksos_inventaris_kso_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formulir_pembersihan_ksos');
    }
}
