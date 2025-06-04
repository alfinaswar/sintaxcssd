<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdMasterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_master_items', function (Blueprint $table) {
            $table->id();
            $table->string('Kode')->nullable();
            $table->string('ROID')->nullable();
            $table->string('AssetId')->nullable();
            $table->string('Nama')->nullable();
            $table->string('Merk')->nullable();
            $table->string('Satuan')->nullable();
            $table->string('Harga')->nullable();
            $table->string('idUser')->nullable();
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
        Schema::dropIfExists('cssd_master_items');
    }
}
