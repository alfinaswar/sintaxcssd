<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenIzinModel extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dok_izin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester',
        'priority',
        'dokumen',
        'namaDokumen',
        'jenisFile',
        'keterangan',
        'tanggalProses',
        'verifikasi',
        'tanggalSelesai',
        'comments',
        'Tanggalcomments',
        'user_at'
    ];
}
