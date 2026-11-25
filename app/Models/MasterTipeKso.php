<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTipeKso extends Model
{
    use HasFactory;

    protected $table = 'master_tipe_kso';

    protected $fillable = [
        'master_alat_kso_id',
        'master_merk_kso_id',
        'nama',
        'nama_rs',
    ];

    public function alat()
    {
        return $this->belongsTo(MasterAlatKso::class, 'master_alat_kso_id');
    }

    public function merk()
    {
        return $this->belongsTo(MasterMerkKso::class, 'master_merk_kso_id');
    }
}
