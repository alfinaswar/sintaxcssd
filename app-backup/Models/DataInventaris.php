<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataInventaris extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nama', 'real_name','kode_item','assetID', 'no_inventaris', 'no_sn','tanggal_beli', 'nama_rs', 'departemen', 'pengguna','gambar','tgl_kalibrasi','tgl_expire','dokumen'];

}
