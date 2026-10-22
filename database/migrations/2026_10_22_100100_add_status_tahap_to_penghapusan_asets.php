<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddStatusTahapToPenghapusanAsets extends Migration
{
    /**
     * Tambah nilai enum Status per tahap approval.
     * Nilai lama (pengajuan, proses, draft) tetap dipertahankan agar data lama valid.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE penghapusan_asets MODIFY COLUMN Status ENUM(
            'pengajuan',
            'proses',
            'draft',
            'smi',
            'jangmed',
            'yanmed',
            'keu',
            'direktur',
            'disetujui',
            'ditolak'
        ) NOT NULL DEFAULT 'smi'");

        DB::table('penghapusan_asets')
            ->whereIn('Status', ['draft', 'pengajuan'])
            ->update(['Status' => 'smi']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('penghapusan_asets')
            ->whereIn('Status', ['smi', 'jangmed', 'yanmed', 'keu', 'direktur'])
            ->update(['Status' => 'pengajuan']);

        DB::statement("ALTER TABLE penghapusan_asets MODIFY COLUMN Status ENUM(
            'pengajuan',
            'disetujui',
            'ditolak',
            'proses',
            'draft'
        ) NOT NULL DEFAULT 'draft'");
    }
}
