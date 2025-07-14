<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTamabahanToDataInventaris extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->string('UserCreate')->nullable()->after('klasifikasi');
            $table->string('UserId')->nullable()->after('UserCreate');
            $table->string('UpdateName')->nullable()->after('UserId');
            $table->string('UpdateById')->nullable()->after('UpdateName');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_inventaris', function (Blueprint $table) {
            //
        });
    }
}
