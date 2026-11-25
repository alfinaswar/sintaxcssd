<?php

namespace Database\Seeders;

use App\Models\MasterAlatKso;
use Illuminate\Database\Seeder;

class MasterAlatKsoSeeder extends Seeder
{
    public function run()
    {
        $alats = [
            'AUTOIMMUNE ANALYZER',
            'BLOOD GAS ANALYZER',
            'CARDIAC MARKER ANALYZER (TROPONIN I)',
            'CHEMISTRY ANALYZER',
            'COAGULATION ANALYZER',
            'CUTTING COAGULAN',
            'ELECTROLYTE ANALYZER',
            'ESR ANALYZER',
            'FLEXIBLE URETEROSCOPY SYSTEM',
            'GLUCOSE TEST SYSTEM',
            'GLUCOSE TEST SYSTEM + KETON',
            'HANDHELD LABEL MAKER',
            'HEMATOLOGY',
            'HEMATOLOGY ANALYZER',
            'HEMODIALYSIS MACHINE',
            'HEMOSTASIS ANALYZER',
            'IMMUNOASSAY ANALYZER',
            'IMMUNOASSAY SCANNER SYSTEM',
            'IMMUNOLOGY ANALYZER',
            'IMMUNOSEROLOGY ANALYZER',
            'KETON TEST SYSTEM',
            'MICROBIOLOGY CULTURE ANALYZER',
            'MICROPLATE READER',
            'MICROPLATE WASHER',
            'MINIVIDAS',
            'MOLECULAR DIAGNOSTIC ANALYZER',
            'PCR ANALYZER',
            'SEALING MACHINE',
            'UREA BREATH TEST ANALYZER',
            'URINE ANALYZER',
        ];

        foreach ($alats as $nama) {
            MasterAlatKso::firstOrCreate(['nama' => $nama]);
        }
    }
}
