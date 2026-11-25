<?php

namespace Database\Seeders;

use App\Models\MasterMerkKso;
use Illuminate\Database\Seeder;

class MasterMerkKsoSeeder extends Seeder
{
    public function run()
    {
        $merks = [
            '36026200008',
            'ABBOTT',
            'ALIFAX',
            'B. BRAUN',
            'BIOMERIEUX',
            'BIOTEK',
            'BIOWAY',
            'BOECO',
            'CANON LIDE 300',
            'CEPHEID',
            'COATRON',
            'COBAS',
            'COBAS C111',
            'COBAS C303',
            'COBAS CIII',
            'COBAS E411',
            'COBAS INTEGRA 400 PLUS',
            'COBAS PURE',
            'COBAS U411',
            'COBLATION',
            'DIAGNOSTIK STAGO',
            'ELEKTROLITE ONELYTE',
            'EUROIMMUN',
            'EUROIMMUN AG-GERMANY/EUROLINE SCAN',
            'FINE CARE',
            'FRESENIUS',
            'GEM 3500IL',
            'GEM 5000',
            'GEM PREMIER TM',
            'GKE',
            'HELENA',
            'HIKIMAGING',
            'I-STAT',
            'ISE 9180',
            'LABUMAT 2/UPA99013',
            'MINDRAY',
            'MINDRAY BC 7800',
            'MINI VIDAS',
            'NOVA 5 CRT',
            'NOVA BIOMEDICAL',
            'NOVA STAT-STRIP XPRESS 2',
            'ONELYTE',
            'ONEMED',
            'PC ONE',
            'PRIME CCS COMP',
            'QUIDEL',
            'RAYTO',
            'ROCHE',
            'STA SATELITE MAX',
            'STAGO',
            'SYSMEX',
            'TERUMO',
            'TERUMO MEDISAFE FIT',
            'TRIAGE METERPRO',
            'VERIFY',
            'VIDAS',
            'VIDAS 30 MD',
            'WONDFO',
        ];

        foreach ($merks as $nama) {
            MasterMerkKso::firstOrCreate(
                ['nama' => $nama],
                ['nama_rs' => null]
            );
        }
    }
}
