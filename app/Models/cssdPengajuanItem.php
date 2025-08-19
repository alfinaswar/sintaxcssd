<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdPengajuanItem extends Model
{
    use HasFactory;
    protected $table = 'cssd_pengajuan_items';
    protected $guarded = ['id'];
}
