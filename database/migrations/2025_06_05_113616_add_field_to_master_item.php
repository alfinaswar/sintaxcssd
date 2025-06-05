<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToMasterItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_master_items', function (Blueprint $table) {
            $table->string('SerialNumber')->nullable()->after('AssetId');
            $table->string('Tipe')->nullable()->after('Merk');
            $table->string('Qty')->nullable()->after('Tipe');
            $table->string('TahunPerolehan')->nullable()->after('Qty');
            $table->string('KondisiBarang')->nullable()->after('TahunPerolehan');
            $table->string('Gambar')->nullable()->after('KondisiBarang');

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
