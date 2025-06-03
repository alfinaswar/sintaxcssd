<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasalahModel extends Model
{
    use HasFactory, SoftDeletes, AutoNumberTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'masalah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_masalah',
        'kode_item',
        'assetID',
        'nama_perangkat',
        'judul',
        'kasus',
        'jumlah_masalah',
        'assetID',
        'jenis',
        'tindakan',
        'waktu_pengerjaan',
        'keterangan',
        'qty',
        'nama_rs'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'kode_masalah' => [
                'format' => 'SR?', // autonumber format. '?' will be replaced with the generated number.
                'length' => 5 // The number of digits in an autonumber
            ]
            
        ];
    }
}
