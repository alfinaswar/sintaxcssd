<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetManagemenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_managemen', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable();
            $table->string('departemen', 50)->nullable();
            $table->string('userPengguna', 100)->nullable();
            $table->string('mac', 20)->nullable();
            $table->string('ipID', 10)->nullable();
            $table->string('noIP', 10)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('jenis', 50)->nullable();
            $table->string('password', 50)->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('asset_managemen');
    }
}
