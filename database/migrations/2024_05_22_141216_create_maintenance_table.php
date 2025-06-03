<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('kd_maintanance', 100)->nullable();
            $table->string('kode_item', 100)->nullable();
            $table->string('assetID', 100)->nullable();
            $table->integer('bulan');
            $table->integer('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('id_user')->nullable();
            $table->string('nama_rs', 2)->nullable();
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
        Schema::dropIfExists('maintenance');
    }
}
