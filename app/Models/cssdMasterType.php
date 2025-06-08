<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdMasterType extends Model
{
    use HasFactory;
    protected $table = 'cssd_master_types';
    protected $guarded = ['id'];
}
