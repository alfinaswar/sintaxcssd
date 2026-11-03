<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $indexesDi = collect(DB::select("SHOW INDEX FROM data_inventaris"))->pluck('Key_name')->all();
        if (!in_array('idx_di_kode_item', $indexesDi)) {
            Schema::table('data_inventaris', function (Blueprint $table) {
                $table->index('kode_item', 'idx_di_kode_item');
            });
        }

        $indexesFp = collect(DB::select("SHOW INDEX FROM formulir_pembersihans"))->pluck('Key_name')->all();
        if (!in_array('idx_fp_kode_item', $indexesFp)) {
            Schema::table('formulir_pembersihans', function (Blueprint $table) {
                $table->index('kode_item', 'idx_fp_kode_item');
            });
        }
        if (!in_array('idx_fp_created_at', $indexesFp)) {
            Schema::table('formulir_pembersihans', function (Blueprint $table) {
                $table->index('created_at', 'idx_fp_created_at');
            });
        }
        if (!in_array('idx_fp_created_by', $indexesFp)) {
            Schema::table('formulir_pembersihans', function (Blueprint $table) {
                $table->index('createdBy', 'idx_fp_created_by');
            });
        }
    }

    public function down(): void
    {
        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->dropIndex('idx_di_kode_item');
        });

        Schema::table('formulir_pembersihans', function (Blueprint $table) {
            $table->dropIndex('idx_fp_kode_item');
            $table->dropIndex('idx_fp_created_at');
            $table->dropIndex('idx_fp_created_by');
        });
    }
};
