<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMerkKso extends Model
{
    use HasFactory;

    protected $table = 'master_merk_kso';

    protected $fillable = [
        'master_alat_kso_id',
        'nama',
        'nama_rs',
    ];

    public function alat()
    {
        return $this->belongsTo(MasterAlatKso::class, 'master_alat_kso_id');
    }

    public function tipes()
    {
        return $this->hasMany(MasterTipeKso::class, 'master_merk_kso_id');
    }
}
