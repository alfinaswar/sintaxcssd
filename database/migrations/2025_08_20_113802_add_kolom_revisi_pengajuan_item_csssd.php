<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKolomRevisiPengajuanItemCsssd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_pengajuan_items', function (Blueprint $table) {
            $table->text('Revisi')->nullable()->after('Keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cssd_pengajuan_items', function (Blueprint $table) {
            //
        });
    }
}
