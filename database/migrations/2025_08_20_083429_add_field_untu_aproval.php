<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUntuAproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_pengajuan_items', function (Blueprint $table) {
            $table->string('ApproveBy', 100)->nullable()->after('Keterangan');
            $table->dateTime('ApproveAt')->nullable()->after('ApproveBy');
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
