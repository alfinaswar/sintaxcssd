<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdItemset extends Model
{
    use HasFactory;
    protected $table = 'cssd_itemsets';
    protected $guarded = ['id'];


    public function DetailItem()
    {
        return $this->hasMany(cssdItemsetDetail::class, 'IdItemset', 'id');
    }

    public function getNamaset()
    {
        return $this->belongsTo(MasterNamaSet::class, 'Nama', 'id');
    }
    public function getrs()
    {
        return $this->belongsTo(MasterRs::class, 'KodeRs', 'kodeRS');
    }

}
