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
}
