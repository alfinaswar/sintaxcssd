<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReorderApprovalToSixStages extends Migration
{
    /**
     * Urutan approval 6 tahap (VersiAlur = 3):
     *   Sign1 SMI
     *   Sign2 Karu/Kanit Ruangan
     *   Sign3 Kadiv Penunjang Medis
     *   Sign4 Kadiv Pelayanan Medis
     *   Sign5 Kadiv Keuangan
     *   Sign6 Direktur
     *
     * @return void
     */
    public function up()
    {
        // 1. JangMed -> PenunjangMed (tahap sama, nama berubah)
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccJangMed AccPenunjangMed ENUM('Y','N') NULL DEFAULT NULL");
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccJangMedPada AccPenunjangMedPada DATETIME NULL DEFAULT NULL");
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccJangMedOleh AccPenunjangMedOleh VARCHAR(255) NULL DEFAULT NULL");

        // 2. AccKaru lama bertipe datetime (ttd "Mengetahui") -> AccKaruPada
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccKaru AccKaruPada DATETIME NULL DEFAULT NULL");

        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->enum('AccKaru', ['Y', 'N'])->nullable()->after('AccSmiOleh');
            $table->string('AccKaruOleh')->nullable()->after('AccKaruPada');
            $table->string('ApprovalToken', 64)->nullable()->unique()->after('VersiAlur');
        });

        // 3. Nilai Status untuk tahap baru
        DB::statement("ALTER TABLE penghapusan_asets MODIFY COLUMN Status ENUM(
            'pengajuan',
            'proses',
            'draft',
            'smi',
            'karu',
            'penunjangmed',
            'jangmed',
            'yanmed',
            'keu',
            'direktur',
            'disetujui',
            'ditolak'
        ) NOT NULL DEFAULT 'smi'");

        // 4. Geser kolom Sign* ke urutan baru untuk baris alur 5 tingkat
        foreach (DB::table('penghapusan_asets')->where('VersiAlur', 2)->get() as $row) {
            DB::table('penghapusan_asets')->where('id', $row->id)->update([
                'Sign6' => $row->Sign5,
                'Sign5' => $row->Sign4,
                'Sign4' => $row->Sign3,
                'Sign3' => $row->Sign2,
                'Sign2' => $row->Sign6,
            ]);
        }

        // 5. Acc*Oleh sekarang menyimpan nama penanda tangan (approval tanpa login),
        //    bukan lagi id user
        $kolomOleh = [
            'AccSmiOleh',
            'AccKaruOleh',
            'AccPenunjangMedOleh',
            'AccYanMedOleh',
            'AccKeuOleh',
            'AccDirekturOleh',
        ];

        $namaUser = DB::table('users')->pluck('name', 'id')->all();

        foreach (DB::table('penghapusan_asets')->get() as $row) {
            $update = [];

            foreach ($kolomOleh as $kolom) {
                if (! Schema::hasColumn('penghapusan_asets', $kolom)) {
                    continue;
                }

                $nilai = $row->{$kolom} ?? null;

                if ($nilai !== null && $nilai !== '' && isset($namaUser[$nilai])) {
                    $update[$kolom] = $namaUser[$nilai];
                }
            }

            if (! empty($update)) {
                DB::table('penghapusan_asets')->where('id', $row->id)->update($update);
            }
        }

        // 6. Token untuk link approval
        foreach (DB::table('penghapusan_asets')->whereNull('ApprovalToken')->get() as $row) {
            DB::table('penghapusan_asets')->where('id', $row->id)->update([
                'ApprovalToken' => substr(hash_hmac('sha256', $row->id . '|' . $row->NomorPengajuan, config('app.key')), 0, 40),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('penghapusan_asets')
            ->whereIn('Status', ['karu', 'penunjangmed'])
            ->update(['Status' => 'smi']);

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

        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->dropColumn(['AccKaru', 'AccKaruOleh', 'ApprovalToken']);
        });

        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccKaruPada AccKaru DATETIME NULL DEFAULT NULL");
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccPenunjangMed AccJangMed ENUM('Y','N') NULL DEFAULT NULL");
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccPenunjangMedPada AccJangMedPada DATETIME NULL DEFAULT NULL");
        DB::statement("ALTER TABLE penghapusan_asets CHANGE AccPenunjangMedOleh AccJangMedOleh VARCHAR(255) NULL DEFAULT NULL");
    }
}
