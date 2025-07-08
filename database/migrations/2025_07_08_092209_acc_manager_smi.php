<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccManagerSmi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->enum('AccManager', ['Y', 'N'])->nullable()->after('Sign1');
            $table->dateTime('AccManagerPada')->nullable()->after('AccManager');
            $table->enum('AccSmi', ['Y', 'N'])->nullable()->after('Sign2');
            $table->dateTime('AccSmiPada')->nullable()->after('AccSmi');
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
