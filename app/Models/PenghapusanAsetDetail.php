<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenghapusanAsetDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'penghapusan_aset_details';
    protected $guarded = ['id'];


    public function getItem()
    {
        return $this->belongsTo(DataInventaris::class, 'AssetId', 'id');
    }
}
