<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaRsToFormulirPembersihans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formulir_pembersihans', function (Blueprint $table) {
            $table->string('nama_rs', 100)->nullable()->after('Keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formulir_pembersihans', function (Blueprint $table) {
            //
        });
    }
}
