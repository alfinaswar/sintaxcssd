<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerksToCssdPengajuanItemDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_pengajuan_item_details', function (Blueprint $table) {
            $table->string('Supplier', 100)->nullable()->after('Merk');
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
