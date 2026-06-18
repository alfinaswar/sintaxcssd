<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaKaruToPenghapusanAsets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->string('NamaKaru', 200)->nullable()->after('Sign3');
            $table->dateTime('AccKaru')->nullable()->after('NamaKaru');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            //
        });
    }
}
