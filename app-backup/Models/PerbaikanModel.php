<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerbaikanModel extends Model
{
    use HasFactory, SoftDeletes, AutoNumberTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'perbaikan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode_perbaikan',
        'assetID',
        'nama_perangkat',
        'jenis_perangkat',
        'user_pemilik',
        'departemen',
        'masalah',
        'status'
    ];

    public function getAutoNumberOptions()
    {
        return [
            'kode_perbaikan' => [
                'format' => 'PE.?', // autonumber format. '?' will be replaced with the generated number.
                'length' => 5 // The number of digits in an autonumber
            ]
        ];
    }
}
