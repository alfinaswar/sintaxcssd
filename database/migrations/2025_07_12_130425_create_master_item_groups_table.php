<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_master_item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('Kode')->nullable();
            $table->string('SerialNumber')->nullable();
            $table->string('Nama')->nullable();
            $table->string('Merk')->nullable();
            $table->string('Kategori')->nullable();
            $table->string('Foto')->nullable();
            $table->string('UserCreate')->nullable();
            $table->string('userEdit')->nullable();
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
        Schema::dropIfExists('master_item_groups');
    }
}
