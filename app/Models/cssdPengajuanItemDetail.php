<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdPengajuanItemDetail extends Model
{
    use HasFactory;
    protected $table = 'cssd_pengajuan_item_details';
    protected $guarded = ['id'];

    public function getMerk()
    {
        return $this->hasOne(cssdMerk::class, 'id', 'Merk');
    }
    public function getType()
    {
        return $this->hasOne(cssdMasterType::class, 'id', 'TypeKategori');
    }
    public function getSupplier()
    {
        return $this->hasOne(cssdMasterSupplier::class, 'id', 'Supplier');
    }
    public function getRs()
    {
        return $this->hasOne(MasterRs::class, 'kodeRS', 'KodeRs');
    }
    public function getHeader()
    {
        return $this->hasOne(cssdPengajuanItem::class, 'id', 'IdPengajuan');
    }
}
