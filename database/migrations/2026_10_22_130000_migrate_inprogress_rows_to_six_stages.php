<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateInprogressRowsToSixStages extends Migration
{
    /**
     * Pengajuan alur 5 tingkat yang masih berjalan dipindah ke alur 6 tingkat.
     * Karu/Kanit adalah tahap baru di posisi 2, sehingga pengajuan yang masih
     * berjalan harus menunggu Karu lebih dulu. Tahap yang sudah disetujui tetap
     * tersimpan di kolom Acc* masing-masing.
     *
     * @return void
     */
    public function up()
    {
        DB::table('penghapusan_asets')
            ->where('VersiAlur', 2)
            ->whereIn('Status', ['smi', 'jangmed', 'yanmed', 'keu', 'direktur', 'proses'])
            ->update([
                'VersiAlur' => 3,
                'Status' => 'karu',
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('penghapusan_asets')
            ->where('VersiAlur', 3)
            ->where('Status', 'karu')
            ->whereNull('AccKaru')
            ->update([
                'VersiAlur' => 2,
                'Status' => 'jangmed',
            ]);
    }
}
