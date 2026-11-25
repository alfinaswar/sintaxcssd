<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeMasterAlatKsoIdNullableInMasterMerkKsoTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE master_merk_kso MODIFY master_alat_kso_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE master_merk_kso MODIFY master_alat_kso_id BIGINT UNSIGNED NOT NULL');
    }
}
