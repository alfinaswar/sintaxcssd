<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnDataInventarisUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->string('unit', 120)->nullable()->after('departemen');
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
            $table->dropColumn('unite');

        });
    }
}
