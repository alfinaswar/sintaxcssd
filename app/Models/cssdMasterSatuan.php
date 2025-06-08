<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdMasterSatuan extends Model
{
    use HasFactory;
    protected $table = 'cssd_master_satuans';
    protected $guarded = ['id'];
}
