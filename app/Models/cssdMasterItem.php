<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdMasterItem extends Model
{
    use HasFactory;
    protected $table = 'cssd_master_items';
    protected $guarded = ['id'];

    public function getNama()
    {
        return $this->belongsTo(MasterItemGroup::class, 'Nama', 'id');
    }
    public function getMerk()
    {
        return $this->belongsTo(cssdMerk::class, 'Merk', 'id');
    }
    public function getTipe()
    {
        return $this->belongsTo(cssdMasterType::class, 'Tipe', 'id');
    }
    public function getSatuan()
    {
        return $this->belongsTo(cssdMasterSatuan::class, 'Satuan', 'id');
    }
    public function getNamaRS()
    {
        return $this->belongsTo(MasterRs::class, 'KodeRs', 'kodeRS');
    }
    public function getItemDalamSet()
    {
        return $this->hasMany(cssdItemsetDetail::class, 'ItemId', 'id');
    }
    // Membuat kode otomatis di model dengan event creating


}
