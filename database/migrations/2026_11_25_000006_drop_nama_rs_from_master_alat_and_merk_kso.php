<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropNamaRsFromMasterAlatAndMerkKso extends Migration
{
    public function up()
    {
        Schema::table('master_alat_kso', function (Blueprint $table) {
            $table->dropColumn('nama_rs');
        });

        Schema::table('master_merk_kso', function (Blueprint $table) {
            $table->dropColumn('nama_rs');
        });
    }

    public function down()
    {
        Schema::table('master_alat_kso', function (Blueprint $table) {
            $table->string('nama_rs')->nullable()->after('nama');
        });

        Schema::table('master_merk_kso', function (Blueprint $table) {
            $table->string('nama_rs')->nullable()->after('tipe');
        });
    }
}
