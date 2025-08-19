<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdMasterSupplier extends Model
{
    use HasFactory;
    protected $table = 'cssd_master_suppliers';
    protected $guarded = ['id'];
}
