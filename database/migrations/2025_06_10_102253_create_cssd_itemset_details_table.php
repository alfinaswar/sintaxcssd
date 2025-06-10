<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssdItemsetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cssd_itemset_details', function (Blueprint $table) {
            $table->id();
            $table->string('IdItemset')->nullable();
            $table->string('ItemId')->nullable();
            $table->string('Qty')->nullable();
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
        Schema::dropIfExists('cssd_itemset_details');
    }
}
