<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetodePenghapusanToPenghapusanAsetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penghapusan_aset_details', function (Blueprint $table) {
            $table->string('Metode', 200)->nullable()->after('Unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penghapusan_aset_details', function (Blueprint $table) {
            //
        });
    }
}
