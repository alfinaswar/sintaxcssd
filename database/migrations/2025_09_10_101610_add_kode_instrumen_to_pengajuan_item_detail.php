<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeInstrumenToPengajuanItemDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_pengajuan_item_details', function (Blueprint $table) {
            $table->string('KodeInstrumen')->nullable()->after('IdPengajuan');
            $table->string('TypeKategori')->nullable()->after('Supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cssd_pengajuan_item_details', function (Blueprint $table) {
            //
        });
    }
}
