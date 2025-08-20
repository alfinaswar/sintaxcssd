<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdPengajuanItem extends Model
{
    use HasFactory;
    protected $table = 'cssd_pengajuan_items';
    protected $guarded = ['id'];

    public function getDiajukan()
    {
        return $this->hasOne(User::class, 'id', 'idUser');
    }
    public function getManager()
    {
        return $this->hasOne(User::class, 'id', 'ApproveBy');
    }

    public function getRs()
    {
        return $this->belongsTo(MasterRs::class, 'KodeRs', 'kodeRS');
    }

    public function getDetail()
    {
        return $this->hasMany(cssdPengajuanItemDetail::class, 'IdPengajuan', 'id');
    }

}
