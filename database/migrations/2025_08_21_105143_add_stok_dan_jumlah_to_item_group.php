<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStokDanJumlahToItemGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cssd_master_item_groups', function (Blueprint $table) {
            $table->string('Idle')->nullable()->after('Kategori');
            $table->string('InUse')->nullable()->after('Idle');
            $table->string('Stok')->nullable()->after('InUse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cssd_master_item_groups', function (Blueprint $table) {
            //
        });
    }
}
