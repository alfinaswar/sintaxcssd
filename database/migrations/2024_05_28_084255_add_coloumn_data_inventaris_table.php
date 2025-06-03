<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnDataInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_inventaris', function (Blueprint $table) {
             	$table->string('RO2ID', 120)->nullable()->after('kode_item');
           	 $table->string('ROID', 120)->nullable()->after('RO2ID');
		$table->string('keterangan', 120)->nullable()->after('dokumen');
		$table->string('manualBook', 120)->nullable()->after('keterangan');;
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
            $table->dropColumn('RO2ID');
		$table->dropColumn('ROID');
		$table->dropColumn('keterangan');
		$table->dropColumn('manualbook');
        });
    }
}
