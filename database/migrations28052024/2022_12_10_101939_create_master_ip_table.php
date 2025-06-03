<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterIpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_ip', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('segmen')->nullable();
            $table->string('ip')->nullable();
            $table->enum('status', [0, 1]);
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
        Schema::dropIfExists('master_ip');
    }
}
