<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillApprovalTokenPenghapusanAsets extends Migration
{
    /**
     * Isi ApprovalToken yang masih kosong.
     *
     * Token dihitung deterministik dari id + NomorPengajuan + APP_KEY, sehingga
     * sama dengan token yang dipakai aplikasi saat memvalidasi link approval.
     *
     * @return void
     */
    public function up()
    {
        foreach (DB::table('penghapusan_asets')->whereNull('ApprovalToken')->orWhere('ApprovalToken', '')->get() as $row) {
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
        // Token tidak dibatalkan agar link approval yang sudah beredar tetap valid
    }
}
