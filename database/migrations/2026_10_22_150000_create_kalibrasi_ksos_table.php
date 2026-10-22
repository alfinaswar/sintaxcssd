<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKalibrasiKsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kalibrasi_ksos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('inventaris_kso_id')->nullable();
            $table->string('AssetID', 100)->nullable();
            $table->string('NamaRS', 20)->nullable();
            $table->string('Nama', 100)->nullable();
            $table->string('Kepemilikan', 100)->nullable();
            $table->date('TglKalibrasi')->nullable();
            $table->date('TglExpire')->nullable();
            $table->text('Keterangan')->nullable();
            $table->string('Dokumen', 255)->nullable();
            $table->string('UserCreate', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('inventaris_kso_id', 'kalibrasi_ksos_inventaris_kso_id_index');
            $table->index('TglExpire', 'kalibrasi_ksos_tgl_expire_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kalibrasi_ksos');
    }
}
