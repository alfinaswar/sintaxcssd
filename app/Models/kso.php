<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kerjasamaoperasional';

    protected $guarded = ['id'];

    public function getNamaBarang()
    {
        return $this->hasOne(DataInventaris::class, 'kode_item', 'KodeAlat');
    }

    public function getKalibrasi()
    {
        return $this->hasOne(KalibrasiModel::class, 'assetID', 'KodeAlat');
    }
}
