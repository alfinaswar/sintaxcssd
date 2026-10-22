<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAccOlehToPenghapusanAsets extends Migration
{
    /**
     * Kolom Sign* menyimpan path file tanda tangan, sehingga identitas
     * penanda tangan disimpan terpisah di kolom Acc*Oleh.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->string('AccSmiOleh')->nullable()->after('AccSmiPada');
            $table->string('AccJangMedOleh')->nullable()->after('AccJangMedPada');
            $table->string('AccYanMedOleh')->nullable()->after('AccYanMedPada');
            $table->string('AccKeuOleh')->nullable()->after('AccKeuPada');
            $table->string('AccDirekturOleh')->nullable()->after('AccDirekturPada');
        });

        // Baris yang terlanjur menyimpan id user di Sign* dipindahkan ke Acc*Oleh
        $pemetaan = [
            'Sign1' => 'AccSmiOleh',
            'Sign2' => 'AccJangMedOleh',
            'Sign3' => 'AccYanMedOleh',
            'Sign4' => 'AccKeuOleh',
            'Sign5' => 'AccDirekturOleh',
        ];

        foreach (DB::table('penghapusan_asets')->where('VersiAlur', 2)->get() as $row) {
            $update = [];

            foreach ($pemetaan as $sign => $oleh) {
                if (! empty($row->{$sign})) {
                    $update[$oleh] = $row->{$sign};
                    $update[$sign] = null;
                }
            }

            if (! empty($update)) {
                DB::table('penghapusan_asets')->where('id', $row->id)->update($update);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->dropColumn([
                'AccSmiOleh',
                'AccJangMedOleh',
                'AccYanMedOleh',
                'AccKeuOleh',
                'AccDirekturOleh',
            ]);
        });
    }
}
