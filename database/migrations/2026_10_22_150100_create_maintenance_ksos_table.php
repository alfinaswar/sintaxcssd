<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceKsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_ksos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('inventaris_kso_id')->nullable();
            $table->string('KodeMaintenance', 100)->nullable();
            $table->string('AssetID', 100)->nullable();
            $table->integer('Bulan');
            $table->integer('Status')->nullable();
            $table->string('Kategori', 100)->nullable();
            $table->text('Keterangan')->nullable();
            $table->string('Dokumentasi', 255)->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('NamaRS', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('inventaris_kso_id', 'maintenance_ksos_inventaris_kso_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance_ksos');
    }
}
