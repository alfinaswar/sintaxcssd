<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsKalibrasiToDataInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::table('data_inventaris', function (Blueprint $table) {
    $table->enum('isKalibrasi', ['0', '1'])->nullable()->after('keterangan');
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
            //
        });
    }
}
