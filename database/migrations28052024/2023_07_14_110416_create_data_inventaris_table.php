<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->default('text');
            $table->string('kode_item', 100)->default('text');
            $table->string('no_inventaris', 100)->nullable()->default('text');
            $table->string('no_sn', 100)->nullable()->default('text');
            $table->string('nama_rs', 100)->nullable()->default('text');
            $table->string('departemen', 100)->nullable()->default('text');
            $table->string('pengguna', 100)->nullable()->default('text');
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
        Schema::dropIfExists('data_inventaris');
    }
}
