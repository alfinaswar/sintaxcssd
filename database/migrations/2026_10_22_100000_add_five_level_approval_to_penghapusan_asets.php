<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFiveLevelApprovalToPenghapusanAsets extends Migration
{
    /**
     * Kolom approval berjenjang.
     * VersiAlur = 1 menandai data lama agar tetap dibaca dengan arti kolom lama
     * (Sign1 = Manager, Sign2 = SMI, Sign3 = file ttd Karu).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penghapusan_asets', function (Blueprint $table) {
            $table->string('Sign5')->nullable()->after('Sign4');
            $table->string('Sign6')->nullable()->after('Sign5');

            $table->enum('AccJangMed', ['Y', 'N'])->nullable()->after('AccSmiPada');
            $table->dateTime('AccJangMedPada')->nullable()->after('AccJangMed');

            $table->enum('AccYanMed', ['Y', 'N'])->nullable()->after('AccJangMedPada');
            $table->dateTime('AccYanMedPada')->nullable()->after('AccYanMed');

            $table->enum('AccKeu', ['Y', 'N'])->nullable()->after('AccYanMedPada');
            $table->dateTime('AccKeuPada')->nullable()->after('AccKeu');

            $table->enum('AccDirektur', ['Y', 'N'])->nullable()->after('AccKeuPada');
            $table->dateTime('AccDirekturPada')->nullable()->after('AccDirektur');

            $table->text('CatatanApproval')->nullable()->after('AccDirekturPada');
            $table->unsignedTinyInteger('VersiAlur')->default(1)->after('CatatanApproval');
        });

        DB::table('penghapusan_asets')->update(['VersiAlur' => 1]);
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
                'Sign5',
                'Sign6',
                'AccJangMed',
                'AccJangMedPada',
                'AccYanMed',
                'AccYanMedPada',
                'AccKeu',
                'AccKeuPada',
                'AccDirektur',
                'AccDirekturPada',
                'CatatanApproval',
                'VersiAlur',
            ]);
        });
    }
}
