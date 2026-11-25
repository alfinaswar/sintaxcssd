<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAlatKso extends Model
{
    use HasFactory;

    protected $table = 'master_alat_kso';

    protected $fillable = [
        'nama',
        'nama_rs',
    ];

    public function merks()
    {
        return $this->hasMany(MasterMerkKso::class, 'master_alat_kso_id');
    }

    public function tipes()
    {
        return $this->hasMany(MasterTipeKso::class, 'master_alat_kso_id');
    }
}
