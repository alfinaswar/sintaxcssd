<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenghapusanAsetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghapusan_aset_details', function (Blueprint $table) {
            $table->id();
            $table->string('idPenghapusan')->nullable();
            $table->string('AssetId')->nullable();
            $table->string('SerialNumber')->nullable();
            $table->string('NoInventaris')->nullable();
            $table->string('Departemen')->nullable();
            $table->string('Unit')->nullable();
            $table->string('Qty')->default('1');
            $table->string('Keterangan')->nullable();
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
        Schema::dropIfExists('penghapusan_aset_details');
    }
}
