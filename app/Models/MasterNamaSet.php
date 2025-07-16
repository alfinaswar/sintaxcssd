<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterNamaSet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'master_nama_sets';
    protected $guarded = ['id'];
}
