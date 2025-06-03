<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataInventaris extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * prote
     *
     * @var array
     */
    protected $table = 'data_inventaris';

    protected $fillable = ['RO2ID', 'harga', 'ROID', 'nama', 'merk', 'real_name', 'kode_item', 'assetID', 'no_inventaris', 'no_sn', 'tanggal_beli', 'nama_rs', 'departemen', 'unit', 'pengguna', 'gambar', 'tgl_kalibrasi', 'tgl_expire', 'dokumen', 'manualbook', 'isKalibrasi', 'keterangan', 'klasifikasi'];

    public function DataMaintenance()
    {
        return $this->hasMany(Maintanance::class, 'kode_item', 'kode_item');
    }

    public function Departemen()
    {
        return $this->hasOne(MasterDepartemenModel::class, 'departemen', 'id');
    }

    public function rumahsakit()
    {
        return $this->hasOne(MasterRs::class, 'kodeRS', 'nama_rs');
    }

    public function getFormPembersihan()
    {
        return $this->belongsTo(FormulirPembersihan::class, 'idAlat', 'id');
    }
}
