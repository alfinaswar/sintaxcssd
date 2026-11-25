<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterVendorKso extends Model
{
    use HasFactory;

    protected $table = 'master_vendor_kso';

    protected $fillable = [
        'nama',
    ];
}
