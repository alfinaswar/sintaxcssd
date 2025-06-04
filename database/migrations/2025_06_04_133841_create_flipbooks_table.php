<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlipbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flipbooks', function (Blueprint $table) {
            $table->id();
            $table->string('Nama', 255)->nullable();
            $table->string('NamaItem', 255)->nullable();
            $table->string('Jenis', 255)->nullable();
            $table->string('RumahSakit', 255)->nullable();
            $table->string('Departemen', 255)->nullable();
            $table->string('TanggalPembelian', 255)->nullable();
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
        Schema::dropIfExists('flipbooks');
    }
}
