<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KalibrasiModel extends Model
{
    use HasFactory;
    protected $table = 'kalibrasi';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assetID',
        'kodeRS',
        'nama',
        'kepemilikan',
        'tgl_kalibrasi',
        'exp_date',
        'keterangan',
        'dokumen'
    ];
}
