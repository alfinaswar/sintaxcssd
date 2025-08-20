<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierDanKodeGrafir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_master_items', function (Blueprint $table) {
            $table->string('Supplier', 100)->nullable()->after('Satuan');
            $table->string('KodeGrafir', 255)->nullable()->after('Kode');
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
