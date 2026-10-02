<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kalibrasi', function (Blueprint $table) {
            $table->index(['assetID', 'id'], 'kalibrasi_assetid_id_index');
            $table->index('exp_date', 'kalibrasi_exp_date_index');
        });

        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->index('nama_rs', 'data_inventaris_nama_rs_index');
        });
    }

    public function down(): void
    {
        Schema::table('kalibrasi', function (Blueprint $table) {
            $table->dropIndex('kalibrasi_assetid_id_index');
            $table->dropIndex('kalibrasi_exp_date_index');
        });

        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->dropIndex('data_inventaris_nama_rs_index');
        });
    }
};
