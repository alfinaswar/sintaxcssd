<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeUnitToCssdPeminjamanBarangs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_peminjaman_barangs', function (Blueprint $table) {
            $table->string('KodeRuanganPenerima')->nullable()->after('RuanganPenerima');
            $table->string('KodeRuanganPeminjam')->nullable()->after('RuanganPeminjam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cssd_peminjaman_barangs', function (Blueprint $table) {
            //
        });
    }
}
