<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAktifToMasterItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_master_items', function (Blueprint $table) {
            $table->enum('Aktif', ['Y', 'N'])->nullable()->default('Y')->after('KodeRs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cssd_master_items', function (Blueprint $table) {
            //
        });
    }
}
