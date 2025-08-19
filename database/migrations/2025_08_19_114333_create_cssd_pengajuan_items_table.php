<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdPengajuanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_pengajuan_items', function (Blueprint $table) {
            $table->id();
            $table->string('Kode')->nullable();
            $table->date('Tanggal')->nullable();
            $table->enum('Status', ['', 'bar'])->nullable()->default(['foo', 'bar']);
            $table->string('KodeRs')->nullable();
            $table->string('idUser')->nullable();
            $table->string('UserCreate')->nullable();
            $table->string('UserUpdate')->nullable();
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
        Schema::dropIfExists('cssd_pengajuan_items');
    }
}
