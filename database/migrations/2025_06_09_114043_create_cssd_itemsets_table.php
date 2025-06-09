<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdItemsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_itemsets', function (Blueprint $table) {
            $table->id();
            $table->string('Nama')->nullable();
            $table->string('Item')->nullable();
            $table->string('KodeRs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cssd_itemsets');
    }
}
