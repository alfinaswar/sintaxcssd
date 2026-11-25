<?php

namespace Database\Seeders;

use App\Models\MasterVendorKso;
use Illuminate\Database\Seeder;

class MasterVendorKsoSeeder extends Seeder
{
    public function run()
    {
        $vendors = [
            'PT. AAM',
            'PT. ANDESLA ANANDA THARRA',
            'PT. ANUGERAH TETAP MAKMUR',
            'PT. ARIANDA PUTRA PRATAMA',
            'PT. B BRAUN MEDICAL INDONESIA',
            'PT. DEXA ARPHINDO PRATAMA',
            'PT. DIAN TRIKARSA',
            'PT. DJEMBATAN DUA',
            'PT. EDISON DUTA SARANA',
            'PT. ENSEVAL PUTERA MEGATRADING',
            'PT. ERA MAJU SEJAHTERA',
            'PT. FRESENIUS MEDICAL CARE INDONESIA',
            'PT. GLOBAL DISPO MEDIKA',
            'PT. INDOMEDIKA MULIA JAYA',
            'PT. INTI MAKMUR MEDITAMA',
            'PT. INTISUMBER HASIL SEMPURNA GLOBAL',
            'PT. LIMA DISPO SELARAS',
            'PT. MEDQUEST JAYA GLOBAL',
            'PT. MITRA INTI DWITAMA',
            'PT. MULTISERA INDOSA',
            'PT. RAFA TOPAZ UTAMA',
            'PT. ROCHE INDONESIA',
            'PT. SARANA MAJU SEJAHTERA',
            'PT. SINERGI PERSADA MEDIKA',
            'PT. TAMARA OVERSEAS CORPORINDO',
            'PT. TIRTA MEDICAL INDONESIA',
            'PT. TRANSMEDIC INDONESIA',
            'PT. UBC',
            'PT. WIDO ABADI SUKSES',
        ];

        foreach ($vendors as $nama) {
            MasterVendorKso::firstOrCreate(['nama' => $nama]);
        }
    }
}
