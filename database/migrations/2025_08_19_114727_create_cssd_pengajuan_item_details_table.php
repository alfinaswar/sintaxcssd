<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdPengajuanItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_pengajuan_item_details', function (Blueprint $table) {
            $table->id();
            $table->string('IdPengajuan')->nullable();
            $table->string('NamaItem')->nullable();
            $table->string('Merk')->nullable();
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
        Schema::dropIfExists('cssd_pengajuan_item_details');
    }
}
