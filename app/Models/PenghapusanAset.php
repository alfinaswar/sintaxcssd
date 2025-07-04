<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenghapusanAset extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'penghapusan_asets';
    protected $guarded = ['id'];

    public function getDepartemen()
    {
        return $this->belongsTo(MasterDepartemenModel::class, 'Departemen', 'id');
    }
    public function getDiajukan()
    {
        return $this->belongsTo(user::class, 'DiajukanOleh', 'id');
    }
    public function getRs()
    {
        return $this->belongsTo(MasterRs::class, 'KodeRS', 'kodeRS');
    }
}
